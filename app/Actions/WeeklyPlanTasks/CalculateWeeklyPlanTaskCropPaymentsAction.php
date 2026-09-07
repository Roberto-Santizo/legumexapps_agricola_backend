<?php

namespace App\Actions\WeeklyPlanTasks;

use App\Errors\NotAcceptable;
use App\Models\Agricola\Crop;
use App\Models\Agricola\CropStep;
use App\Models\Agricola\WeeklyPlanTaskCrop;
use App\Models\Agricola\WeeklyPlanTaskEmployeePayment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Motor de calculo de los pagos de una tarea de cosecha.
 *
 * Corre como un paso independiente del cierre: los operarios de finca cierran la
 * tarea con las libras y los inputs, y los operarios administrativos disparan
 * este calculo despues.
 */
class CalculateWeeklyPlanTaskCropPaymentsAction
{
    public const OPERATION_ADDITION = 'addition';

    public const OPERATION_SUSTRACTION = 'sustraction';

    public const OPERATION_DIVIDE = 'divide';

    public const OPERATION_MULTIPLICATION = 'multiplication';

    public const OPERATION_LOOK_UP = 'look_up';

    public const OPERATIONS = [
        self::OPERATION_ADDITION,
        self::OPERATION_SUSTRACTION,
        self::OPERATION_DIVIDE,
        self::OPERATION_MULTIPLICATION,
        self::OPERATION_LOOK_UP,
    ];

    /**
     * Clave del contexto que se reemplaza por las libras de cada empleado.
     */
    public const CONTEXT_EMPLOYEE_LBS = 'employee_lbs';

    /**
     * Claves que la formula del cultivo debe producir para poder generar el pago.
     */
    public const CONTEXT_HOURS = 'hours';

    public const CONTEXT_AMOUNT = 'amount';

    public function execute(WeeklyPlanTaskCrop $task): void
    {
        $crop = $this->resolveCrop($task);
        $steps = $crop->steps->sortBy('step_order')->values();

        $this->validateCropConfiguration($crop, $steps);
        $this->validateTaskEmployees($task);

        $baseContext = $this->createContext($task, $crop);
        $this->validateRequiredInputs($crop, $baseContext);

        DB::transaction(function () use ($task, $crop, $steps, $baseContext) {
            $this->clearPreviousPayments($task);

            foreach ($task->employees as $employee) {
                $context = [...$baseContext, self::CONTEXT_EMPLOYEE_LBS => (float) $employee->lbs];

                foreach ($steps as $step) {
                    $this->executeStep($crop, $step, $context);
                }

                $this->validateResult($context);
                $this->createPayment($context, $task, $employee);
            }

            $task->status = WeeklyPlanTaskCrop::STATUS_CALCULATED;
            $task->save();
        });
    }

    private function resolveCrop(WeeklyPlanTaskCrop $task): Crop
    {
        $task->loadMissing('cdp.crop');

        if (! $task->cdp) {
            throw new NotAcceptable('La tarea de cosecha no tiene un CDP asociado');
        }

        if (! $task->cdp->crop) {
            throw new NotAcceptable("El CDP {$task->cdp->name} no tiene un cultivo asignado");
        }

        return $task->cdp->crop->load(['inputs', 'parameters', 'ranges', 'steps']);
    }

    private function validateCropConfiguration(Crop $crop, Collection $steps): void
    {
        if ($steps->isEmpty()) {
            throw new NotAcceptable("El cultivo {$crop->name} no tiene pasos de cálculo configurados");
        }

        $invalid = $steps->first(fn (CropStep $step) => ! in_array($step->operation, self::OPERATIONS, true));

        if ($invalid) {
            throw new NotAcceptable("La operación '{$invalid->operation}' del cultivo {$crop->name} no es soportada");
        }
    }

    private function validateTaskEmployees(WeeklyPlanTaskCrop $task): void
    {
        $task->loadMissing('employees');

        if ($task->employees->isEmpty()) {
            throw new NotAcceptable('La tarea no cuenta con empleados asignados');
        }

        if ($task->employees->contains(fn ($employee) => $employee->lbs === null)) {
            throw new NotAcceptable('La tarea cuenta con empleados sin libras registradas');
        }
    }

    private function validateRequiredInputs(Crop $crop, array $context): void
    {
        $missing = $crop->inputs
            ->filter(fn ($input) => $input->required && ! array_key_exists($input->key, $context))
            ->map(fn ($input) => $input->label)
            ->values();

        if ($missing->isNotEmpty()) {
            throw new NotAcceptable('Faltan los valores para: '.$missing->implode(', '));
        }
    }

    private function validateResult(array $context): void
    {
        $missing = collect([self::CONTEXT_HOURS, self::CONTEXT_AMOUNT])
            ->reject(fn (string $key) => array_key_exists($key, $context));

        if ($missing->isNotEmpty()) {
            throw new NotAcceptable('El cálculo del cultivo no produjo los valores de horas y monto');
        }
    }

    private function createPayment(array $context, WeeklyPlanTaskCrop $task, mixed $employee): void
    {
        WeeklyPlanTaskEmployeePayment::create([
            'code' => $employee->code,
            'name' => $employee->name,
            'hours' => $context[self::CONTEXT_HOURS],
            'amount' => $context[self::CONTEXT_AMOUNT],
            'task_crop_id' => $task->id,
            'weekly_plan_id' => $task->weekly_plan_id,
            'date' => $task->operation_date,
            'theorical_hours' => 0,
        ]);
    }

    private function clearPreviousPayments(WeeklyPlanTaskCrop $task): void
    {
        $task->payments()->delete();
    }

    private function executeStep(Crop $crop, CropStep $step, array &$context): void
    {
        switch ($step->operation) {
            case self::OPERATION_ADDITION:
                $context[$step->result_key] = $this->resolveOperand($step, $step->left, $context)
                    + $this->resolveOperand($step, $step->right, $context);
                break;
            case self::OPERATION_SUSTRACTION:
                $context[$step->result_key] = $this->resolveOperand($step, $step->left, $context)
                    - $this->resolveOperand($step, $step->right, $context);
                break;
            case self::OPERATION_MULTIPLICATION:
                $context[$step->result_key] = $this->resolveOperand($step, $step->left, $context)
                    * $this->resolveOperand($step, $step->right, $context);
                break;
            case self::OPERATION_DIVIDE:
                $this->divideOperation($step, $context);
                break;
            case self::OPERATION_LOOK_UP:
                $this->lookUpOperation($crop, $step, $context);
                break;
            default:
                throw new NotAcceptable("La operación '{$step->operation}' no es soportada");
        }
    }

    private function divideOperation(CropStep $step, array &$context): void
    {
        $divisor = $this->resolveOperand($step, $step->right, $context);

        if ($divisor == 0.0) {
            throw new NotAcceptable("El paso '{$step->result_key}' intenta dividir entre cero");
        }

        $context[$step->result_key] = $this->resolveOperand($step, $step->left, $context) / $divisor;
    }

    private function lookUpOperation(Crop $crop, CropStep $step, array &$context): void
    {
        $value = $this->resolveOperand($step, $step->left, $context);

        $range = $crop->ranges
            ->where('key', $step->left)
            ->first(fn ($range) => $range->min_value <= $value && $range->max_value >= $value);

        if (! $range) {
            throw new NotAcceptable("No existe un rango configurado para '{$step->left}' con el valor {$value}");
        }

        $context[$step->result_key] = $range->result;
    }

    /**
     * Un operando es un numero literal o la clave de un valor ya presente en el contexto.
     */
    private function resolveOperand(CropStep $step, string $token, array $context): float
    {
        if (is_numeric($token)) {
            return (float) $token;
        }

        if (! array_key_exists($token, $context)) {
            throw new NotAcceptable("El paso '{$step->result_key}' referencia la clave '{$token}' que no existe");
        }

        if (! is_numeric($context[$token])) {
            throw new NotAcceptable("El valor de '{$token}' no es numérico");
        }

        return (float) $context[$token];
    }

    private function createContext(WeeklyPlanTaskCrop $task, Crop $crop): array
    {
        $task->loadMissing('weeklyPlanTaskCropInputs.input');

        $inputs = $task->weeklyPlanTaskCropInputs
            ->filter(fn ($input) => $input->input !== null)
            ->mapWithKeys(fn ($input) => [$input->input->key => $input->value])
            ->toArray();

        $parameters = $crop->parameters
            ->mapWithKeys(fn ($parameter) => [$parameter->key => $parameter->value])
            ->toArray();

        return [...$inputs, ...$parameters];
    }
}

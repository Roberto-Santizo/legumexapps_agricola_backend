<?php

namespace App\Actions\WeeklyPlanTasks;

use App\Models\Agricola\Crop;
use App\Models\Agricola\CropStep;
use App\Models\Agricola\WeeklyPlanTaskCrop;
use App\Models\Agricola\WeeklyPlanTaskEmployeePayment;

class CloseWeeklyPlanTaskCropAction
{
    public function execute(WeeklyPlanTaskCrop $task): void
    {
        $crop = $task->cdp->crop->load(['inputs', 'parameters', 'ranges', 'steps']);
        $this->validateTaskCropPayments($task);
        $context = $this->createContext($task, $crop);

        foreach($task->employees as $employee){
            foreach ($crop->steps as $step) {
                $context['employee_lbs'] = $employee->lbs;
                $this->executeStep($crop, $step, $context);
            }
            $this->createPayment($context, $task, $employee);
        }

        $task->status = 4;
        $task->save();
    }

    private function createPayment(array $context, WeeklyPlanTaskCrop $task, mixed $employee)
    {
        WeeklyPlanTaskEmployeePayment::create([
            'code' => $employee->code,
            'name' => $employee->name,
            'hours' => $context['hours'],
            'amount' => $context['amount'],
            'task_crop_id' => $task->id,
            'weekly_plan_id' => $task->weekly_plan_id,
            'date' => $task->operation_date,
            'theorical_hours' => 0
        ]);
    }

    private function validateTaskCropPayments(WeeklyPlanTaskCrop $task)
    {
        $task->payments()->delete();
    }

    private function executeStep(Crop $crop, CropStep $step, array &$context)
    {
        switch ($step->operation) {
            case 'addition':
                $this->additionOperation($step, $context);
                break;
            case 'sustraction':
                $this->sustractionOperation($step, $context);
                break;
            case 'divide':
                $this->divideOperation($step, $context);
                break;
            case 'multiplication':
                $this->multiplicationOperation($step, $context);
                break;
            case 'look_up':
                $this->lookUpOperation($crop, $step, $context);
                break;
            default:
                break;
        }
    }

    private function sustractionOperation(CropStep $step, array &$context)
    {
        $left = $context[$step->left];
        $right = $context[$step->right];
        $context[$step->result_key] = $left - $right;
    }

    private function additionOperation(CropStep $step, array &$context)
    {
        $left = $context[$step->left];
        $right = $context[$step->right];
        $context[$step->result_key] = $left + $right;
    }

    private function divideOperation(CropStep $step, array &$context)
    {
        $left = $context[$step->left];
        $right = $context[$step->right];
        $context[$step->result_key] = $left / $right;
    }

    private function lookUpOperation(Crop $crop, CropStep $step, array &$context)
    {
        $value = $context[$step->left];
        $range = $crop->ranges()->where('key', $step->left)->where('min_value', '<=', $value)->where('max_value', '>=', $value)->first();
        $context[$step->result_key] = $range->result;
    }

    private function multiplicationOperation(CropStep $step, array &$context)
    {
        $left = $context[$step->left];
        $right = $context[$step->right];
        $context[$step->result_key] = $left * $right;
    }

    private function createContext(WeeklyPlanTaskCrop $task, Crop $crop)
    {
        $weeklyPlanTaskCropInputs = [];
        $parameters = [];
        $inputs = $task->weeklyPlanTaskCropInputs;
        $parameters = $crop->parameters;

        $weeklyPlanTaskCropInputs = $inputs->mapWithKeys(function ($input) {
            return [$input->input->key => $input->value];
        })->toArray();

        $parameters = $parameters->mapWithKeys(function ($input) {
            return [$input->key => $input->value];
        })->toArray();

        return [...$weeklyPlanTaskCropInputs, ...$parameters];
    }
}

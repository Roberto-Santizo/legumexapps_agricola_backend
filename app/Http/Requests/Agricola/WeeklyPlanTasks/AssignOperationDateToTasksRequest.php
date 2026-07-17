<?php

namespace App\Http\Requests\Agricola\WeeklyPlanTasks;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignOperationDateToTasksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tasks' => ['required', 'array', 'min:1'],
            'tasks.*' => ['integer', Rule::exists('task_weekly_plans', 'id')],
            'operation_date' => ['required', 'date'],
            'finca_group_id' => ['required', 'numeric', 'exists:finca_groups,id']
        ];
    }

    public function messages(): array
    {
        return [
            'tasks.required' => 'El listado de tareas es obligatorio.',
            'tasks.array' => 'El listado de tareas debe ser un arreglo.',
            'tasks.min' => 'Debe incluir al menos una tarea.',

            'tasks.*.integer' => 'El identificador de la tarea debe ser numérico.',
            'tasks.*.exists' => 'Una o más tareas seleccionadas no existen.',

            'operation_date.required' => 'La fecha de operación es obligatoria.',
            'operation_date.date' => 'La fecha de operación debe tener un formato válido.',

            'finca_group_id.required' => 'El grupo es requerido',
            'finca_group_id.numeric' => 'El grupo debe de ser un dato númerico',
            'finca_group_id.exists' => 'El grupo seleccionado no existe',
        ];
    }
}

<?php

namespace App\Http\Requests\Agricola\WeeklyPlanTasks;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWeeklyPlanTaskRequest extends FormRequest
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
            'budget' =>                         ['required', 'numeric', 'min:1'],
            'hours' =>                          ['required', 'numeric'],
            'operation_date' =>                 ['sometimes', 'nullable', 'date'],
            'extraordinary' =>                  ['required', 'boolean'],
            'weekly_plan_id' =>                 ['required', 'numeric', 'exists:weekly_plans,id'],
            'tarea_id' =>                       ['required', 'numeric', 'exists:tareas,id'],
            'plantation_control_id' =>          ['required', 'numeric', 'exists:plantation_controls,id'],
            'finca_group_id' =>                 ['sometimes',  'nullable', 'numeric', 'exists:finca_groups,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'budget.required' => 'El presupuesto es obligatorio.',
            'budget.numeric' => 'El presupuesto debe ser un valor numérico.',
            'budget.min' => 'El presupuesto debe ser mayor a 0.',

            'hours.required' => 'Las horas son obligatorias.',
            'hours.numeric' => 'Las horas deben ser un valor numérico.',

            'operation_date.required' => 'La fecha de operación es obligatoria.',
            'operation_date.date' => 'La fecha de operación debe tener un formato válido.',

            'extraordinary.required' => 'El campo extraordinario es obligatorio.',
            'extraordinary.boolean' => 'El campo extraordinario debe ser verdadero o falso.',

            'weekly_plan_id.required' => 'El plan semanal es obligatorio.',
            'weekly_plan_id.numeric' => 'El identificador del plan semanal debe ser numérico.',
            'weekly_plan_id.exists' => 'El plan semanal seleccionado no existe.',

            'tarea_id.required' => 'La tarea es obligatoria.',
            'tarea_id.numeric' => 'El identificador de la tarea debe ser numérico.',
            'tarea_id.exists' => 'La tarea seleccionada no existe.',

            'plantation_control_id.required' => 'El control de plantación es obligatorio.',
            'plantation_control_id.numeric' => 'El identificador del control de plantación debe ser numérico.',
            'plantation_control_id.exists' => 'El control de plantación seleccionado no existe.',

            'finca_group_id.required' => 'El grupo de fincas es obligatorio.',
            'finca_group_id.numeric' => 'El identificador del grupo de fincas debe ser numérico.',
            'finca_group_id.exists' => 'El grupo de fincas seleccionado no existe.',
        ];
    }
}

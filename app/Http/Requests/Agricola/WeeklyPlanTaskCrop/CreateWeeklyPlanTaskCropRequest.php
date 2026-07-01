<?php

namespace App\Http\Requests\Agricola\WeeklyPlanTaskCrop;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateWeeklyPlanTaskCropRequest extends FormRequest
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
            'plantation_control_id' =>      ['required', 'numeric', 'exists:plantation_controls,id'],
            'tarea_id' =>                    ['required', 'numeric', 'exists:tareas,id'],
            'weekly_plan_id' =>              ['required', 'numeric', 'exists:weekly_plans,id'],
            'operation_date' =>              ['required', 'string', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'plantation_control_id.required' => 'El control de plantación es obligatorio.',
            'plantation_control_id.numeric'  => 'El control de plantación debe ser un valor numérico.',
            'plantation_control_id.exists'   => 'El control de plantación seleccionado no existe.',

            'tarea_id.required' => 'La tarea es obligatoria.',
            'tarea_id.numeric'  => 'La tarea debe ser un valor numérico.',
            'tarea_id.exists'   => 'La tarea seleccionada no existe.',

            'weekly_plan_id.required' => 'El plan semanal es obligatorio.',
            'weekly_plan_id.numeric'  => 'El plan semanal debe ser un valor numérico.',
            'weekly_plan_id.exists'   => 'El plan semanal seleccionado no existe.',

            'operation_date.required' => 'La fecha de operación es obligatoria.',
            'operation_date.string'   => 'La fecha de operación debe ser una cadena de texto.',
            'operation_date.date'     => 'La fecha de operación no tiene un formato válido.',
        ];
    }
}

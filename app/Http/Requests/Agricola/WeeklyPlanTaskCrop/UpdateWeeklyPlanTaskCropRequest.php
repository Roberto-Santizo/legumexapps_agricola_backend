<?php

namespace App\Http\Requests\Agricola\WeeklyPlanTaskCrop;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWeeklyPlanTaskCropRequest extends FormRequest
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
            'plantation_control_id' => ['sometimes', 'numeric', 'exists:plantation_controls,id'],
            'tarea_id' => ['sometimes', 'numeric', 'exists:tareas,id'],
            'operation_date' => ['sometimes', 'date_format:Y-m-d'],
        ];
    }

    public function messages(): array
    {
        return [
            'plantation_control_id.numeric' => 'El control de plantación debe ser un valor numérico.',
            'plantation_control_id.exists' => 'El control de plantación seleccionado no existe.',

            'tarea_id.numeric' => 'La tarea debe ser un valor numérico.',
            'tarea_id.exists' => 'La tarea seleccionada no existe.',

            'operation_date.date_format' => 'La fecha de operación debe tener el formato Y-m-d.',
        ];
    }
}

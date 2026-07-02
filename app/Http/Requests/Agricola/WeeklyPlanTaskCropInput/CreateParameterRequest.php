<?php

namespace App\Http\Requests\Agricola\WeeklyPlanTaskCropInput;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateParameterRequest extends FormRequest
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
            'task_crop_weekly_plan_id' =>   ['required', 'exists:task_crop_weekly_plans,id'],
            'crop_input_id'=>               ['required', 'exists:crop_inputs,id'],
            'value'=>                       ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'task_crop_weekly_plan_id.required' => 'El plan semanal de cultivo es obligatorio.',
            'task_crop_weekly_plan_id.exists'   => 'El plan semanal de cultivo seleccionado no existe.',

            'crop_input_id.required' => 'El insumo es obligatorio.',
            'crop_input_id.exists'   => 'El insumo seleccionado no existe.',

            'value.required' => 'El valor es obligatorio.',
            'value.numeric'  => 'El valor debe ser un número.',
        ];
    }
}

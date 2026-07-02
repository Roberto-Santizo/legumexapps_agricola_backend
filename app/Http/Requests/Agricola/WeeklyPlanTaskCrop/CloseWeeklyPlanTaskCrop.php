<?php

namespace App\Http\Requests\Agricola\WeeklyPlanTaskCrop;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CloseWeeklyPlanTaskCrop extends FormRequest
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
            'task_crop_weekly_plan_id' =>       ['required', 'exists:task_crop_weekly_plans,id'],
            'inputs' =>                         ['required', 'array'],
            'inputs.*.crop_input_id'=>          ['required', 'exists:crop_inputs,id'],
            'inputs.*.value'=>                  ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'task_crop_weekly_plan_id.required' => 'El plan semanal de cultivo es obligatorio.',
            'task_crop_weekly_plan_id.exists'   => 'El plan semanal de cultivo seleccionado no existe.',

            'inputs.required' => 'Debe enviar al menos un insumo.',
            'inputs.array'    => 'El formato de los insumos es inválido.',

            'inputs.*.crop_input_id.required' => 'El insumo es obligatorio.',
            'inputs.*.crop_input_id.exists'   => 'El insumo seleccionado no existe.',

            'inputs.*.value.required' => 'El valor del insumo es obligatorio.',
            'inputs.*.value.numeric'  => 'El valor del insumo debe ser un número.',
        ];
    }
}

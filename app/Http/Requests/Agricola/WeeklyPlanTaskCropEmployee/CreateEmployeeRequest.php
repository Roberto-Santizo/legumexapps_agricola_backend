<?php

namespace App\Http\Requests\Agricola\WeeklyPlanTaskCropEmployee;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
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
            'name' =>                       ['required', 'string'],
            'code' =>                       ['required', 'string'],
            'lbs' =>                        ['nullable', 'numeric'],
            'task_crop_weekly_plan_id' =>   ['required', 'numeric', 'exists:task_crop_weekly_plans,id']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',

            'code.required' => 'El código es obligatorio.',
            'code.string' => 'El código debe ser una cadena de texto.',

            'lbs.numeric' => 'El campo libras debe ser un valor numérico.',

            'task_crop_weekly_plan_id.required' => 'El plan semanal de cultivo es obligatorio.',
            'task_crop_weekly_plan_id.numeric' => 'El identificador del plan semanal de cultivo debe ser un número.',
            'task_crop_weekly_plan_id.exists' => 'El plan semanal de cultivo seleccionado no existe.',
        ];
    }
}

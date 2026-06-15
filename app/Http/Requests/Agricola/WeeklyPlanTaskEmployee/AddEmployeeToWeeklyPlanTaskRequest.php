<?php

namespace App\Http\Requests\Agricola\WeeklyPlanTaskEmployee;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddEmployeeToWeeklyPlanTaskRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
            'task_weekly_plan_id' => ['required', 'numeric', 'exists:task_weekly_plans,id']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',

            'code.required' => 'El código es obligatorio.',
            'code.string' => 'El código debe ser una cadena de texto.',

            'task_weekly_plan_id.required' => 'La tarea obligatoria.',
            'task_weekly_plan_id.numeric' => 'La tarea debe de ser un dato númerico',
            'task_weekly_plan_id.exists' => 'La tarea no dexiste',
        ];
    }
}

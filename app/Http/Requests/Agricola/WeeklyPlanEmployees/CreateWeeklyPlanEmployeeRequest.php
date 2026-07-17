<?php

namespace App\Http\Requests\Agricola\WeeklyPlanEmployees;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateWeeklyPlanEmployeeRequest extends FormRequest
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
            'employees' => ['required', 'array'],
            'weekly_plan_id' => ['required', 'exists:weekly_plans,id'],
            'finca_group_id' => ['required', 'exists:finca_groups,id']
        ];
    }

    public function messages(): array
    {
        return [
            'employees.required' => 'Los empleados son requeridos',
            'employees.array' => 'Los empleados deben de ser un arreglo',

            'weekly_plan_id.required' => 'El plan semanal es obligatorio.',
            'weekly_plan_id.exists' => 'El plan semanal seleccionado no existe.',

            'finca_group_id.exists' => 'El grupo de fincas seleccionado no existe.',
        ];
    }
}

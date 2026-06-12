<?php

namespace App\Http\Requests\Agricola\WeeklyPlanEmployees;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddEmployeesToGroupRequest extends FormRequest
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
            'employees' => ['required', 'array', 'min:1'],
            'employees.*' => ['required', 'integer', 'exists:weekly_assignment_employees,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'employees.required' => 'Debe enviar al menos un empleado.',
            'employees.array' => 'El campo empleados debe ser un arreglo.',
            'employees.min' => 'Debe seleccionar al menos un empleado.',

            'employees.*.required' => 'El empleado es obligatorio.',
            'employees.*.integer' => 'El identificador del empleado debe ser un número entero.',
            'employees.*.exists' => 'Uno o más empleados seleccionados no existen.',
        ];
    }
}

<?php

namespace App\Http\Requests\Agricola\AnnualSalaries;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAnnualSalaryRequest extends FormRequest
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
            'year' => ['required', 'integer', Rule::unique('annual_salaries', 'year')->ignore($this->route('annual_salary'))],
            'amount' => ['required', 'numeric']
        ];
    }

    public function messages(): array
    {
        return [
            'year.required' => 'El año es requerido',
            'year.integer' => 'El año debe de ser un número entero',
            'year.unique' => 'Ya existe un salario anual configurado para este año',
            'amount.required' => 'El monto es requerido',
            'amount.numeric' => 'El monto debe de ser un número'
        ];
    }
}

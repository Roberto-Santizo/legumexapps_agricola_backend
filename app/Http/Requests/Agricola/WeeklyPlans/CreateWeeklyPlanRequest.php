<?php

namespace App\Http\Requests\Agricola\WeeklyPlans;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateWeeklyPlanRequest extends FormRequest
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
            'week' => ['required', 'numeric'],
            'year' => ['required', 'numeric'],
            'finca_id' => ['required', 'exists:fincas,id']
        ];
    }

    public function messages(): array
    {
        return [
            'week.required' => 'La semana es requerida',
            'week.numeric' => 'La semana debe de ser un dato númerico',
            'year.required' => 'La semana es requerida',
            'year.numeric' => 'La semana debe de ser un dato númerico',
            'finca_id.required' => 'La finca es requerida',
            'finca_id.exists' => 'La finca ingresada no existe'
        ];
    }
}

<?php

namespace App\Http\Requests\Agricola\DraftWeeklyPlan;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateDraftWeeklyPlanRequest extends FormRequest
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
            'week' =>       ['required', 'numeric', 'integer', 'min:1', 'max:53'],
            'year' =>       ['required', 'numeric', 'integer'],
            'finca_id' =>   ['required', 'exists:fincas,id']
        ];
    }

    public function messages(): array
    {
        return [
            'week.required' => 'La semana es requerida',
            'week.numeric' => 'La semana debe de ser un dato númerico',
            'week.integer' => 'La semana debe de ser un dato entero',
            'week.min' => 'El número de semana minimo es 1',
            'week.max' => 'El número de semana maximo es 53',
            'year.required' => 'La semana es requerida',
            'year.numeric' => 'La semana debe de ser un dato númerico',
            'year.integer' => 'La semana debe de ser un dato entero',
            'finca_id.required' => 'La finca es requerida',
            'finca_id.exists' => 'La finca ingresada no existe'
        ];
    }
}

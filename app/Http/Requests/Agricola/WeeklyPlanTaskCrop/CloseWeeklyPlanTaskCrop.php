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
     * La tarea se identifica por el {id} de la ruta, no por el cuerpo.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inputs' => ['required', 'array', 'min:1'],
            'inputs.*.crop_input_id' => ['required', 'distinct', 'exists:crop_inputs,id'],
            'inputs.*.value' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'inputs.required' => 'Debe enviar al menos un parámetro.',
            'inputs.array' => 'El formato de los parámetros es inválido.',
            'inputs.min' => 'Debe enviar al menos un parámetro.',

            'inputs.*.crop_input_id.required' => 'El parámetro es obligatorio.',
            'inputs.*.crop_input_id.distinct' => 'No puede enviar el mismo parámetro dos veces.',
            'inputs.*.crop_input_id.exists' => 'El parámetro seleccionado no existe.',

            'inputs.*.value.required' => 'El valor del parámetro es obligatorio.',
            'inputs.*.value.numeric' => 'El valor del parámetro debe ser un número.',
        ];
    }
}

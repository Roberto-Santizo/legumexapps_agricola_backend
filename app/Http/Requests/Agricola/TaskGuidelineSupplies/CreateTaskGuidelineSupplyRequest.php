<?php

namespace App\Http\Requests\Agricola\TaskGuidelineSupplies;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateTaskGuidelineSupplyRequest extends FormRequest
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
            'task_guideline_id' =>          ['required', 'numeric', 'exists:task_guidelines,id'],
            'insumo_id'=>                   ['required', 'numeric', 'exists:insumos,id'],
            'quantity'=>                    ['required', 'numeric']
        ];
    }

    public function messages(): array
    {
        return [
            'task_guideline_id.required' => 'El campo guía de tarea es obligatorio.',
            'task_guideline_id.numeric'  => 'El campo guía de tarea debe ser un número.',
            'task_guideline_id.exists'   => 'La guía de tarea seleccionada no es válida.',

            'insumo_id.required' => 'El campo insumo es obligatorio.',
            'insumo_id.numeric'  => 'El campo insumo debe ser un número.',
            'insumo_id.exists'   => 'El insumo seleccionado no es válido.',

            'quantity.required' => 'El campo cantidad es obligatorio.',
            'quantity.numeric'  => 'El campo cantidad debe ser un número.',
        ];
}
}

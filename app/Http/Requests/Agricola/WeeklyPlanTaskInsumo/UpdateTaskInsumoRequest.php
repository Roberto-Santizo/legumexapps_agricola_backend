<?php

namespace App\Http\Requests\Agricola\WeeklyPlanTaskInsumo;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskInsumoRequest extends FormRequest
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
            'assigned_quantity' => ['required', 'numeric'],
            'used_quantity' => ['required', 'numeric'],
            'insumo_id' => ['required', 'numeric', 'exists:insumos,id']
        ];
    }

    public function messages(): array
    {
        return [
            'assigned_quantity.required' => 'La cantidad asignada es obligatoria.',
            'assigned_quantity.numeric' => 'La cantidad asignada debe ser un valor numérico.',

            'used_quantity.required' => 'La cantidad utilizada es obligatoria.',
            'used_quantity.numeric' => 'La cantidad utilizada debe ser un valor numérico.',

            'insumo_id.required' => 'El insumo es obligatorio.',
            'insumo_id.numeric' => 'El identificador del insumo debe ser un valor numérico.',
            'insumo_id.exists' => 'El insumo seleccionado no existe.',
        ];
    }
}

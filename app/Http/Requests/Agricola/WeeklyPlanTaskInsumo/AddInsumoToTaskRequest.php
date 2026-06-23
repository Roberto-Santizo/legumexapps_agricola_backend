<?php

namespace App\Http\Requests\Agricola\WeeklyPlanTaskInsumo;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddInsumoToTaskRequest extends FormRequest
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
            'task_weekly_plan_id' =>    ['required', 'numeric', 'exists:task_weekly_plans,id'],
            'assigned_quantity' =>      ['required', 'numeric'],
            'used_quantity' =>          ['sometimes', 'nullable' ,'numeric'],
            'insumo_id' =>              ['required', 'numeric', 'exists:insumos,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'task_weekly_plan_id.required' => 'La tarea es obligatoria',
            'task_weekly_plan_id.numeric' => 'El identificador de la tarea debe ser un valor numérico.',
            'task_weekly_plan_id.exists' => 'La tarea no existe.',

            'assigned_quantity.required' => 'La cantidad asignada es obligatoria.',
            'assigned_quantity.numeric' => 'La cantidad asignada debe ser un valor numérico.',

            'used_quantity.numeric' => 'La cantidad utilizada debe ser un valor numérico.',

            'insumo_id.required' => 'El insumo es obligatorio.',
            'insumo_id.numeric' => 'El identificador del insumo debe ser un valor numérico.',
            'insumo_id.exists' => 'El insumo seleccionado no existe.',
        ];
    }
}

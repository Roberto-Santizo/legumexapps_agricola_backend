<?php

namespace App\Http\Requests\Agricola\TaskGuidelines;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateTaskGuidelineRequest extends FormRequest
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
            'hours_per_size' =>             ['required', 'numeric'],
            'week'=>                        ['required', 'numeric'],
            'finca_id'=>                    ['required', 'numeric', 'exists:fincas,id'],
            'task_id'=>                     ['required', 'numeric', 'exists:tareas,id'],
            'recipe_id'=>                   ['required', 'numeric', 'exists:recipes,id'],
            'crop_id'=>                     ['required', 'numeric', 'exists:crops,id']
        ];
    }

    public function messages(): array
    {
        return [
            'hours_per_size.required' => 'El campo horas por tamaño es obligatorio.',
            'hours_per_size.numeric'  => 'El campo horas por tamaño debe ser un número.',

            'week.required' => 'El campo semana es obligatorio.',
            'week.numeric'  => 'El campo semana debe ser un número.',

            'finca_id.required' => 'La finca es obligatoria.',
            'finca_id.numeric'  => 'El identificador de la finca debe ser un número.',
            'finca_id.exists'   => 'La finca seleccionada no existe.',

            'task_id.required' => 'La tarea es obligatoria.',
            'task_id.numeric'  => 'El identificador de la tarea debe ser un número.',
            'task_id.exists'   => 'La tarea seleccionada no existe.',

            'recipe_id.required' => 'La receta es obligatoria.',
            'recipe_id.numeric'  => 'El identificador de la receta debe ser un número.',
            'recipe_id.exists'   => 'La receta seleccionada no existe.',

            'crop_id.required' => 'El cultivo es obligatorio.',
            'crop_id.numeric'  => 'El identificador del cultivo debe ser un número.',
            'crop_id.exists'   => 'El cultivo seleccionado no existe.',
        ];
    }
}

<?php

namespace App\Http\Requests\Agricola\Cdps;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCdpRequest extends FormRequest
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
        $id = $this->route('cdp')?->id ?? $this->route('cdp');

        return [
            'name' => ['required', Rule::unique('plantation_controls', 'name')->ignore($id), 'max:15'],
            'total_plants' => ['required', 'numeric'],
            'lote_id' => ['required', 'numeric', 'exists:lotes,id'],
            'recipe_id' => ['required', 'numeric', 'exists:recipes,id'],
            'crop_id' => ['required', 'numeric', 'exists:crops,id'],
            'start_date' => ['required', 'date']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del CDP es requerido',
            'name.unique' => 'El nombre ingresado ya esta registrado',
            'name.max' => 'El nombre del CDP no puede ser de más de 15 digitos',
            'total_plants.required' => 'El total de plantas es requerido',
            'total_plants.numeric' => 'El total de plantas debe de ser un dato númerico',
            'lote_id.required' => 'El lote es requerido',
            'lote_id.numeric' => 'El lote debe de ser un dato númerico',
            'lote_id.exists' => 'El lote no existe',
            'recipe_id.required' => 'La receta es requerida',
            'recipe_id.numeric' => 'La receta debe de ser un dato númerico',
            'recipe_id.exists' => 'La receta no existe',
            'crop_id.required' => 'El cultivo es requerido',
            'crop_id.numeric' => 'El cultivo debe de ser un dato númerico',
            'crop_id.exists' => 'El cultivo no existe',
            'start_date.required' => 'La fecha de inicio es requerida',
            'start_date.date' => 'La fecha de inicio debe de tener formato de fecha',
        ];
    }
}

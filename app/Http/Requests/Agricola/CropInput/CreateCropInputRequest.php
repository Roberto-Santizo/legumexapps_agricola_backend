<?php

namespace App\Http\Requests\Agricola\CropInput;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCropInputRequest extends FormRequest
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
            'crop_id' =>            ['required', 'numeric', 'exists:crops,id'],
            'key' =>                ['required', 'string'],
            'label' =>              ['required', 'string'],
            'default_value' =>      ['required', 'numeric']
        ];
    }

    public function messages(): array
    {
        return [
            'crop_id.required' => 'El campo cultivo es obligatorio.',
            'crop_id.numeric' => 'El campo cultivo debe ser un valor numérico.',
            'crop_id.exists' => 'El cultivo seleccionado no existe.',

            'key.required' => 'El campo clave es obligatorio.',
            'key.string' => 'El campo clave debe ser una cadena de texto.',

            'label.required' => 'El campo etiqueta es obligatorio.',
            'label.string' => 'El campo etiqueta debe ser una cadena de texto.',

            'default_value.required' => 'El campo valor por defecto es obligatorio.',
            'default_value.numeric' => 'El campo valor por defecto debe ser un valor numérico.',
        ];
    }
}

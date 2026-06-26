<?php

namespace App\Http\Requests\Agricola\CropParameters;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCropParameterRequest extends FormRequest
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
            'crop_id' =>            ['required', 'exists:crops,id'],
            'key' =>                ['required', 'string'],
            'value' =>              ['required', 'numeric']
        ];
    }

    public function messages(): array
    {
        return [
            'crop_id.required' =>   'El campo cultivo es obligatorio.',
            'crop_id.exists' =>     'El cultivo seleccionado no existe.',
            'key.required' =>       'El campo clave es obligatorio.',
            'key.string' =>         'El campo clave debe ser una cadena de texto.',
            'value.required' =>     'El campo valor es obligatorio.',
            'value.numeric' =>      'El campo valor debe ser una valor númerico.',
        ];
    }
}

<?php

namespace App\Http\Requests\Agricola\CropRanges;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCropRangeRequest extends FormRequest
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
            'crop_id' => ['required', 'numeric', 'exists:crops,id'],
            'key' => ['required', 'string'],
            'min_value' => ['required', 'numeric'],
            'max_value' => ['required', 'numeric', 'gte:min_value'],
            'result' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'crop_id.required' => 'El cultivo es obligatorio.',
            'crop_id.numeric' => 'El identificador del cultivo debe ser un número.',
            'crop_id.exists' => 'El cultivo seleccionado no existe.',

            'key.required' => 'La clave es obligatoria.',
            'key.string' => 'La clave debe ser una cadena de texto.',

            'min_value.required' => 'El valor mínimo es obligatorio.',
            'min_value.numeric' => 'El valor mínimo debe ser un número.',

            'max_value.required' => 'El valor máximo es obligatorio.',
            'max_value.numeric' => 'El valor máximo debe ser un número.',
            'max_value.gte' => 'El valor máximo debe ser mayor o igual al valor mínimo.',

            'result.required' => 'El resultado es obligatorio.',
            'result.numeric' => 'El resultado debe ser un número.',
        ];
    }
}

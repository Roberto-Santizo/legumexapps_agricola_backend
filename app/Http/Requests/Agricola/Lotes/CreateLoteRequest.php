<?php

namespace App\Http\Requests\Agricola\Lotes;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateLoteRequest extends FormRequest
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
            'name' => ['required'],
            'finca_id' => ['required', 'exists:fincas,id'],
            'total_plants' => ['required'],
            'size' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido',
            'finca_id.required' => 'La finca es requerida',
            'finca_id.exists' => 'La finca ingresada no existe',
            'total_plants.required' => 'El total de plantas es requerida',
            'size.required' => 'El tamaño es requerido'
        ];
    }
}

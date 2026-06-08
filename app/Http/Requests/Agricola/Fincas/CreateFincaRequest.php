<?php

namespace App\Http\Requests\Agricola\Fincas;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateFincaRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'code' => ['required', 'string', 'unique:fincas,code'],
            'terminal_id'=> ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la finca es obligatorio.',
            'name.string' => 'El nombre de la finca debe ser una cadena de texto.',
            'code.required' => 'El código de la finca es obligatorio.',
            'code.string' => 'El código de la finca debe ser una cadena de texto.',
            'code.unique' => 'El código de la finca ya existe.',
            'terminal_id.required' => 'El ID del terminal es obligatorio.',
            'terminal_id.integer' => 'El ID del terminal debe ser un número entero.',
        ];
    }
}

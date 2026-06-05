<?php

namespace App\Http\Requests\Agricola;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'code' => ['required', 'unique:tareas,id,except,' . $this->id],
            'description' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido',
            'code.required' => 'El código es requerido',
            'code.unique' => 'El código utilizado ya existe',
            'description.required' => 'La descripción es requerida'
        ];
    }
}

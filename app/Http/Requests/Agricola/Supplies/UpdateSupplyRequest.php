<?php

namespace App\Http\Requests\Agricola\Supplies;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplyRequest extends FormRequest
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
        $id = $this->route('supply')?->id ?? $this->route('supply');

        return [
            'name' => ['required', 'string'],
            'code' => ['required', 'string',  Rule::unique('insumos', 'code')->ignore($id)],
            'measure' => ['required', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del insumo es requerido',
            'name.string' => 'El nombre del insumo debe de ser un string',
            'code.required' => 'El código del insumo es requerido',
            'code.string' => 'El código del insumo debe de ser un string',
            'code.unique' => 'El código ingresado ya existe',
            'measure.required' => 'La unidad de medida es requerida',
            'measure.string' => 'La únidad de medida debe de ser un string'
        ];
    }
}

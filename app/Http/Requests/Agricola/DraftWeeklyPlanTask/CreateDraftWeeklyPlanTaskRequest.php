<?php

namespace App\Http\Requests\Agricola\DraftWeeklyPlanTask;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateDraftWeeklyPlanTaskRequest extends FormRequest
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
            'task_guideline_id' =>          ['required', 'numeric', 'exists:task_guidelines,id'],
            'draft_weekly_plan_id'=>        ['required', 'numeric', 'exists:draft_weekly_plans,id'],
            'plantation_control_id'=>       ['required', 'numeric', 'exists:plantation_controls,id'],
            'hours'=>                       ['required', 'numeric'],
            'budget'=>                      ['required', 'numeric']
        ];
    }

    public function messages(): array
    {
        return [
            'task_guideline_id.required' => 'El lineamiento de la tarea es obligatorio.',
            'task_guideline_id.numeric'  => 'El lineamiento de la tarea debe ser un valor numérico.',
            'task_guideline_id.exists'   => 'El lineamiento de la tarea seleccionado no existe.',

            'draft_weekly_plan_id.required' => 'El plan semanal es obligatorio.',
            'draft_weekly_plan_id.numeric'  => 'El plan semanal debe ser un valor numérico.',
            'draft_weekly_plan_id.exists'   => 'El plan semanal seleccionado no existe.',

            'hours.required' => 'Las horas son obligatorias.',
            'hours.numeric'  => 'Las horas deben ser un valor numérico.',

            'budget.required' => 'El presupuesto es obligatorio.',
            'budget.numeric'  => 'El presupuesto debe ser un valor numérico.',
        ];
    }
}

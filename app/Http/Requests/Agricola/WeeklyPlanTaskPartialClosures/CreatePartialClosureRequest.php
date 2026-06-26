<?php

namespace App\Http\Requests\Agricola\WeeklyPlanTaskPartialClosures;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePartialClosureRequest extends FormRequest
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
            'task_weekly_plan_id' => ['required', 'numeric', 'exists:task_weekly_plans,id'],
            'start_date' => ['required', 'date'],
            'end_date' =>   ['required', 'date'],
        ];
    }

    public function messages(): array 
    {
        return [
            'task_weekly_plan_id.required' => 'La tarea es requerida',
            'task_weekly_plan_id.numeric' => 'La tarea debe de ser un dato númerico',
            'task_weekly_plan_id.exists' => 'La tarea no existe',
            
            'start_date.required' => 'La fecha de inicio es requierda',
            'start_date.date' => 'La fecha de inicio debe de tener un formato de fecha',
            'end_date.required' => 'La fecha de cierre es requerida',
            'end_date.date' => 'La fecha de cierre debe de tener un formato de fecha',
        ];
    }
}

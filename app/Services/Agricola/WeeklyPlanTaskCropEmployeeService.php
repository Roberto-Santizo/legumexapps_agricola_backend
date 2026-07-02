<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanTaskCropEmployeeServiceInterface;
use App\Models\Agricola\WeeklyPlanTaskCropEmployee;
use Override;

class WeeklyPlanTaskCropEmployeeService implements WeeklyPlanTaskCropEmployeeServiceInterface 
{
    #[Override]
    public function createWeeklyPlanTaskCropEmployee(array $data)
    {
        $newEmployee = WeeklyPlanTaskCropEmployee::create($data);
        return $newEmployee;
    }

    #[Override]
    public function getWeeklyPlanTaskCropEmployees(?string $taskId)
    {
        if(!$taskId) throw new BadRequestError("El ID de la tarea es requerido");
        $employees = WeeklyPlanTaskCropEmployee::where('task_crop_weekly_plan_id', $taskId)->get();
        return $employees;
    }

    #[Override]
    public function getWeeklyPlanTaskCropEmployeeById(string $id)
    {
        $employee = WeeklyPlanTaskCropEmployee::find($id);
        if(!$employee) throw new NotFoundError("El empleado no existe");
        return $employee;
        
    }

    #[Override]
    public function updateWeeklyPlanTaskCropEmployee(array $data, string $id)
    {
        $employee = $this->getWeeklyPlanTaskCropEmployeeById($id);
        $employee->update($data);
        return true;
    }

    #[Override]
    public function deleteWeeklyPlanTaskCropEmployeeById(string $id)
    {
        $employee = $this->getWeeklyPlanTaskCropEmployeeById($id);
        $employee->delete();
        return true;
    }
}
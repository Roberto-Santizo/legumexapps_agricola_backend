<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanTaskEmployeeServiceInterface;
use App\Models\Agricola\WeeklyPlanTaskEmployee;
use Illuminate\Support\Collection;
use Override;

class WeeklyPlanTaskEmployeeService implements WeeklyPlanTaskEmployeeServiceInterface 
{
    #[Override]
    public function addEmployeeToWeeklyPlanTask(array $data)
    {
        $newEmployee = WeeklyPlanTaskEmployee::create($data);
        return $newEmployee;
    }

    #[Override]
    public function getWeeklyPlanTaskEmployees(?string $id)
    {
        if(!$id) throw new BadRequestError("El ID de la tarea es requerido");
        $employees = WeeklyPlanTaskEmployee::where('task_weekly_plan_id', '=', $id, null)->get();
        return $employees;
    }

    #[Override]
    public function getWeeklyPlanTaskEmployeeById(string $id)
    {
        $employee = WeeklyPlanTaskEmployee::find($id, ['*']);
        if(!$employee) throw new NotFoundError("El empleado no existe");
        return $employee;
    }

    #[Override]
    public function updateWeeklyPlanEmployeeById(array $data, string $id)
    {
        $employee = $this->getWeeklyPlanTaskEmployeeById($id);
        $employee->update($data);
        return $employee;
    }

    #[Override]
    public function deleteWeeklyPlanEmployeeById(string $id)
    {
        $employee = $this->getWeeklyPlanTaskEmployeeById($id);
        $employee->delete();
        return true;
    }

    #[Override]
    public function insertEmployeesToWeeklyPlanTask(Collection $data, string $taskId)
    {
        $employeesArray = [];
        
        foreach ($data as $employee) {
            $newEmployee = [
                'name' => $employee->name,
                'code' => $employee->code,
                'task_weekly_plan_id' => $taskId
            ];

            $employeesArray[] = $newEmployee;
        }

        WeeklyPlanTaskEmployee::insert($employeesArray);
        return true;
    }

}
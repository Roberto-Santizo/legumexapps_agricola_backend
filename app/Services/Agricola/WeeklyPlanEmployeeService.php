<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanEmployeeServiceInterface;
use App\Models\Agricola\WeeklyPlanEmployee;
use Override;

class WeeklyPlanEmployeeService implements WeeklyPlanEmployeeServiceInterface
{
    #[Override]
    public function createWeeklyPlanEmployee(array $data)
    {
        $now = now();

        $payload = array_map(fn (array $employee) => [
            "name" => $employee['name'],
            "code" => $employee['code'],
            "weekly_plan_id" => $data['weekly_plan_id'],
            "finca_group_id" => $data['finca_group_id'],
            "created_at" => $now,
            "updated_at" => $now,
        ], $data['employees']);

        WeeklyPlanEmployee::insert($payload);
        return true;
    }

    #[Override]
    public function getWeeklyPlanEmployees(string $weeklyPlanId)
    {
        $employees = WeeklyPlanEmployee::where('weekly_plan_id', '=', $weeklyPlanId)->get();
        return $employees;
    }

    #[Override]
    public function getWeeklyPlanEmployeeById(string $id)
    {
        $employee = WeeklyPlanEmployee::find($id, ['id', 'name', 'code', 'weekly_plan_id', 'finca_group_id']);
        if (!$employee) throw new NotFoundError("Empleado no encontrado");
        return $employee;
    }

    #[Override]
    public function updateWeeklyPlanEmployeeById(array $data, string $id)
    {
        $employee = $this->getWeeklyPlanEmployeeById($id);
        $employee->update($data);
        return $employee;
    }

    #[Override]
    public function deleteWeeklyPlanEmployeeById(string $id)
    {
        $employee = $this->getWeeklyPlanEmployeeById($id);
        $employee->delete();
        return $employee;
    }

    #[Override]
    public function addEmployeesToFincaGrup(array $employeesIds, string $groupId)
    {
        foreach ($employeesIds['employees'] as $id) {
            WeeklyPlanEmployee::where('id', '=', $id, null)->update(['finca_group_id' => $groupId]);
        }
    }
}

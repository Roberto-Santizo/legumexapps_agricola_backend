<?php

namespace App\Services\Employees;

use App\Interfaces\Employees\EmployeesServiceInterface;
use App\Models\Agricola\WeeklyPlan;
use Illuminate\Support\Facades\Http;
use Override;

class EmployeesService implements EmployeesServiceInterface 
{
    #[Override]
    public function getWeeklyPlanEmployees(WeeklyPlan $plan)
    {
        $employees = $plan->employees;
        return $employees->pluck('code')->toArray();
    }

    #[Override]
    public function getFincaEmployees(WeeklyPlan $weeklyPlan)
    {
        $finca = $weeklyPlan->finca;
        $entries = collect();

        if($weeklyPlan->finca->code == 'FLS'){
            $url = env('BIOMETRICO_URL')."/employees?department_id={$finca->department_id}";
            $entries = Http::withHeaders(['Authorization' => env('BIOMETRICO_APP_KEY')])->get($url)->collect();
            $url2 = env('BIOMETRICO_URL')."/employees?department_id=7";
            $entries2 = Http::withHeaders(['Authorization' => env('BIOMETRICO_APP_KEY')])->get($url2)->collect();
            $entries = $entries->collect()->merge($entries2->collect());
        }else {
            $url = env('BIOMETRICO_URL')."/employees?department_id={$finca->department_id}";
            $entries = Http::withHeaders(['Authorization' => env('BIOMETRICO_APP_KEY')])->get($url)->collect();
        }



        $assignedEmployees = $this->getWeeklyPlanEmployees($weeklyPlan);
        $filteredEmployees = [];

        foreach ($entries as $employee) {
            if(in_array($employee['code'], $assignedEmployees)) continue;
            $filteredEmployees[] = $employee;
        }

        return $filteredEmployees;
    }
}
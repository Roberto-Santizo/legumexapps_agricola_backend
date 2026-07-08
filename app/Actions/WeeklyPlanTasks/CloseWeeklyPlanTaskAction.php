<?php

namespace App\Actions\WeeklyPlanTasks;

use App\Models\Agricola\Finca;
use App\Models\Agricola\WeeklyPlan;
use App\Models\Agricola\WeeklyPlanTask;
use App\Models\Agricola\WeeklyPlanTaskEmployeePayment;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CloseWeeklyPlanTaskAction
{
    public function execute(WeeklyPlanTask $task): void
    {
       
        $this->validateTaskPayments($task);

        if ($task->partialClosures->count() == 0) {
            $this->closeTaskWithNoClosures($task);
        }else{
            $this->closeTaskWithClosures($task);
        }

    }

    private function closeTaskWithNoClosures(WeeklyPlanTask $task) 
    {
        $entries = $this->getEntries($task->operation_date->format('Y-m-d'), $task->weeklyPlan->finca);
        $real_employees = [];

        foreach ($task->employees as $employee) {
            $employeeEntries = $entries->where('code', $employee->code)->first();
            
            if($employeeEntries){
                $real_employees[] = $employee;
            }
        }

        $hours = $task->total_hours();
        $theorical_hours =  count($real_employees) >  0 ? $task->hours / count($real_employees) : 0;
        $amount =  count($real_employees) > 0 ? $task->budget / count($real_employees) : 0;

        foreach ($real_employees as $employee) {
            WeeklyPlanTaskEmployeePayment::create([
                'code' => $employee->code,
                'name' => $employee->name,
                'emp_id' => $employee->employee_id,
                'hours' => $hours,
                'amount' => $amount,
                'task_plan_id' => $task->id,
                'weekly_plan_id' => $task->weekly_plan_id,
                'date' => $task->start_date,
                'theorical_hours' => $theorical_hours
            ]);
        }
    }

    private function closeTaskWithClosures(WeeklyPlanTask $task)
    {
        $entries = $this->getWeeklyEntries($task->weeklyPlan);
        $dates = [];
        $formattedEmployees = [];

        foreach ($task->partialClosures as $index => $closure) {
            $dates[$index][] = $closure->start_date;
            $dates[$index][] = $closure->end_date;
        }

        $all_dates = array_merge(...$dates);
        array_unshift($all_dates, $task->start_date);
        array_push($all_dates, $task->end_date);

        $groupedByDay = array_reduce($all_dates, function ($carry, $datetime) {
            $date = $datetime->format('Y-m-d');
            $carry[$date][] = $datetime->toDateTimeString();
            return $carry;
        }, []);

        foreach ($groupedByDay as $key => $dayDates) {
            if (count($dayDates) < 2) {
                throw new Exception("El día {$key} no tiene 2 fechas. Datos: " . json_encode($dayDates));
            }

            try {
                $first_date = Carbon::parse($dayDates[0]);
                $second_date = Carbon::parse($dayDates[1]);
                $groupedByDay[$key] = $first_date->diffInHours($second_date);
            } catch (\Throwable $th) {
                throw new Exception($th->getMessage());
            }
        }


        $formattedEmployees = $task->employees->map(function ($employeeAssignment) use ($groupedByDay, $entries) {
            $employeeAssignment->total_hours = 0;
            $employeeAssignment->dates = [];

            $dates = [];
            foreach ($groupedByDay as $day => $hours) {
                $employeeEntries = $entries->where('code', $employeeAssignment->code)->first();
                
                $exists = $employeeEntries ? collect($employeeEntries['transactions'])->contains(function ($transaction) use ($day) {
                    return Carbon::parse($transaction['punch_time'])->toDateString() === $day;
                }) : false;

                if ($exists) {
                    try {
                        $dates[$day][] = $hours;
                        $employeeAssignment->dates = $dates;
                        $employeeAssignment->total_hours += $hours;
                    } catch (\Throwable $th) {
                        throw new Exception($th->getMessage());
                    }
                }
            }

            return $employeeAssignment;
        });

        $total_hours = $formattedEmployees->reduce(function ($carry, $emp) {
            if (empty($emp->dates)) return $carry;
            return $carry + array_sum(array_merge(...array_values($emp->dates)));
        }, 0);


        foreach ($formattedEmployees as $employeeAssignment) {
            foreach ($employeeAssignment->dates as $day => $hours) {
                if (empty($hours)) {
                    throw new Exception("Empleado {$employeeAssignment->code} no tiene horas válidas en {$day}");
                }
                $percentage = array_sum($hours) / $total_hours;
                $date = Carbon::parse($day);

                WeeklyPlanTaskEmployeePayment::create([
                    'code' =>                   $employeeAssignment->code,
                    'name' =>                   $employeeAssignment->name,
                    'emp_id' =>                 $employeeAssignment->employee_id,
                    'hours' =>                  ($task->hours * $percentage),
                    'amount' =>                 ($task->budget * $percentage),
                    'task_plan_id' =>           $task->id,
                    'weekly_plan_id' =>         $task->weekly_plan_id,
                    'date' =>                   $date,
                    'theorical_hours' =>        $task->hours / $task->employees()->count()
                ]);
            }
        }
    } 

    private function validateTaskPayments(WeeklyPlanTask $task)
    {
        if($task->payments->count() > 0) $task->payments()->delete();
    }

    private function getWeeklyEntries(WeeklyPlan $weeklyPlan): Collection
    {
        $entries = collect();
        $startOfWeek = Carbon::now()->setISODate($weeklyPlan->year, $weeklyPlan->week)->startOfWeek();
        $endOfWeek = Carbon::now()->setISODate($weeklyPlan->year, $weeklyPlan->week)->endOfWeek();

        $url = env('BIOMETRICO_URL')."/transactions/{$weeklyPlan->finca->terminal_id}?start_date={$startOfWeek}&end_date={$endOfWeek}";
        $entries = Http::withHeaders(['Authorization' => env('BIOMETRICO_APP_KEY')])->get($url)->collect();

        return $entries;
    }

    private function getEntries(string $operation_date, Finca $finca): Collection
    {
        $entries = collect();

        $url = env('BIOMETRICO_URL')."/transactions/{$finca->terminal_id}?start_date={$operation_date}&end_date={$operation_date}";
        $entries = Http::withHeaders(['Authorization' => env('BIOMETRICO_APP_KEY')])->get($url)->collect();

        return $entries;
    }
}

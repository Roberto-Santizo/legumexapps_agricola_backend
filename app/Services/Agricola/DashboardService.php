<?php

namespace App\Services\Agricola;

use App\Interfaces\Agricola\DashboardServiceInterface;
use App\Models\Agricola\WeeklyPlan;
use App\Models\Agricola\WeeklyPlanTask;
use Override;

class DashboardService implements DashboardServiceInterface
{
    #[Override]
    public function summaryTasksByFinca(int $week, int $year)
    {
        $plans = WeeklyPlan::where('week', $week)->where('year', $year)->get();
        return $plans;
    }

    #[Override]
    public function getActiveTasks(int $week, int $year)
    {
        $query = WeeklyPlanTask::query();

        $query->whereHas('weeklyPlan', function($q2) use($week, $year){
            $q2->where('week', $week);
            $q2->where('year', $year);
        });

        $query->whereNotNull('start_date');
        $query->whereNull('end_date');

        return $query->get();
    }
}
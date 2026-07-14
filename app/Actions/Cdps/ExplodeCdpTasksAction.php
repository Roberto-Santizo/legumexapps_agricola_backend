<?php

namespace App\Actions\Cdps;

use App\Models\Agricola\AnnualSalary;
use App\Models\Agricola\Cdp;
use App\Models\Agricola\DraftWeeklyPlanTask;
use App\Models\Agricola\TaskGuideline;
use App\Models\DraftWeeklyPlan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class ExplodeCdpTasksAction
{
    public function execute(Cdp $cdp): void
    {
        $cdp->loadMissing('lote');
        $lote = $cdp->lote;

        $tasks = TaskGuideline::where('finca_id', $lote->finca_id)
            ->where('recipe_id', $cdp->recipe_id)
            ->where('crop_id', $cdp->crop_id)
            ->get();

        $weekStarts = $this->getWeekStartDates($cdp->start_date, $cdp->end_date);
        $plans = $this->getOrCreatePlans($weekStarts, $lote->finca_id);
        $amountPerHour = AnnualSalary::all();

        $now = Carbon::now();
        $rows = [];

        foreach ($weekStarts as $index => $startOfWeek) {
            $tasksPerWeek = $tasks->where('week', $index + 1);
            $plan = $plans->get($startOfWeek->weekOfYear . '-' . $startOfWeek->year);

            foreach ($tasksPerWeek as $task) {
                $hours = $lote->size * $task->hours_per_size;

                $rows[] = [
                    'task_guideline_id' => $task->id,
                    'draft_weekly_plan_id' => $plan->id,
                    'plantation_control_id' => $cdp->id,
                    'hours' => $hours,
                    'budget' => $hours * $amountPerHour->last()->amount,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!empty($rows)) {
            DraftWeeklyPlanTask::insert($rows);
        }
    }

    /**
     * Fetches existing draft weekly plans for the given week start dates in a single
     * query, creates any missing ones in a single bulk insert, and returns all of
     * them keyed by "week-year".
     */
    public function getOrCreatePlans(array $weekStarts, int $finca_id): Collection
    {
        $pairs = collect($weekStarts)->map(fn (Carbon $date) => ['week' => $date->weekOfYear, 'year' => $date->year])->unique(fn (array $pair) => $pair['week'] . '-' . $pair['year']);
        $weeks = $pairs->pluck('week')->unique()->values()->all();
        $years = $pairs->pluck('year')->unique()->values()->all();

        $fetchExisting = 
            fn () => DraftWeeklyPlan::where('finca_id', $finca_id)
            ->whereIn('week', $weeks)
            ->whereIn('year', $years)
            ->get()
            ->keyBy(fn (DraftWeeklyPlan $plan) => $plan->week . '-' . $plan->year);

        $existing = $fetchExisting();

        $missing = $pairs->reject(fn (array $pair) => $existing->has($pair['week'] . '-' . $pair['year']));

        if ($missing->isNotEmpty()) {
            $now = Carbon::now();
            $data = $missing->map(fn (array $pair) => ['week' => $pair['week'], 'year' => $pair['year'], 'finca_id' => $finca_id, 'created_at' => $now, 'updated_at' => $now])->values()->all();

            DraftWeeklyPlan::insert($data);

            $existing = $fetchExisting();
        }

        return $existing;
    }

    public function getWeekStartDates(string|Carbon $startDate, string|Carbon $endDate): array
    {
        $start = Carbon::parse($startDate)->startOfWeek(Carbon::MONDAY);
        $end = Carbon::parse($endDate)->startOfWeek(Carbon::MONDAY);

        $period = CarbonPeriod::create($start, '1 week', $end);

        return collect($period)->map(fn (Carbon $date) => $date)->values()->all();
    }
}

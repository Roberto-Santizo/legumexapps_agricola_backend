<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanServiceInterface;
use App\Models\Agricola\WeeklyPlan;
use Override;

class WeeklyPlanService implements WeeklyPlanServiceInterface
{
    #[Override]
    public function getWeeklyPlanByParams(array $params)
    {
        $weekly_plan = WeeklyPlan::where(['week' => $params['week'], 'year' => $params['year'], 'finca_id' => $params['finca_id']])->first();
        if ($weekly_plan) throw new BadRequestError('El plan semanal ya existe');
        return $weekly_plan;
    }

    #[Override]
    public function createWeeklyPlan(array $data)
    {
        $this->getWeeklyPlanByParams($data);

        $weekly_plan = WeeklyPlan::create($data);
        return $weekly_plan;
    }

    #[Override]
    public function getWeeklyPlans(?string $limit)
    {
        $query = WeeklyPlan::query();

        if ($limit) return $query->paginate($limit);

        return $query->get();
    }

    #[Override]
    public function getWeeklyPlanById(string $id)
    {
        $weekl_plan = WeeklyPlan::find($id, ['id', 'week', 'year', 'finca_id']);
        if (!$weekl_plan) throw new NotFoundError("El plan semanal no existe");
        return $weekl_plan;
    }

    #[Override]
    public function updateWeeklyPlan(array $data, string $id)
    {
        throw new \Exception('Not implemented');
    }
}

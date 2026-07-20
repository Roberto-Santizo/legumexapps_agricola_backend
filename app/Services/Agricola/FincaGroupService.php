<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\FincaGroupServiceInterface;
use App\Interfaces\Agricola\WeeklyPlanServiceInterface;
use App\Models\Agricola\FincaGroup;
use App\Models\Agricola\WeeklyPlan;
use Override;

class FincaGroupService implements FincaGroupServiceInterface
{
    #[Override]
    public function createFincaGroup(array $data)
    {
        $group = FincaGroup::create($data);
        return $group;
    }

    #[Override]
    public function getGroups(?string $limit)
    {
        $query = FincaGroup::query();
        $query->orderBy('id', 'DESC');

        if ($limit) return $query->paginate($limit);

        return $query->get();
    }

    #[Override]
    public function getGroupById(string $id)
    {
        $group = FincaGroup::where(['id' => $id])->first();
        if (!$group) throw new NotFoundError("El grupo no existe");
        return $group;
    }

    #[Override]
    public function getGroupByCode(string $code, ?string $weeklyPlanId)
    {
        if(!$weeklyPlanId) throw new BadRequestError("El ID del plan es requerido");
        
        $group = FincaGroup::where('code', $code)
            ->with('employees', function ($q) use ($weeklyPlanId) {
                $q->where('weekly_plan_id', $weeklyPlanId);
            })
            ->first();
        if (!$group) throw new NotFoundError("El grupo no existe");
        return $group;
    }

    #[Override]
    public function getGroupsSummaryByWeeklyPlan(WeeklyPlan $plan)
    {
        $groups = FincaGroup::where('finca_id', $plan->finca_id)
            ->withWhereHas('employees', function ($q) use ($plan) {
                $q->where('weekly_plan_id', $plan->id);
            })
            ->get();
        return $groups;
    }
}

<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\DraftWeeklyPlanServiceInterface;
use App\Models\DraftWeeklyPlan;
use Override;

class DraftWeeklyPlanService implements DraftWeeklyPlanServiceInterface
{
    #[Override]
    public function createDraftWeeklyPlan(array $data)
    {
        $draft = DraftWeeklyPlan::create($data);
        return $draft;
    }

    #[Override]
    public function getDraftWeeklyPlans(?string $limit)
    {
        $query = DraftWeeklyPlan::query();

        if($limit){
            return $query->paginate($limit);
        }

        return $query->get();
    }

    #[Override]
    public function getDraftWeeklyPlanById(string $id)
    {
        $draft = DraftWeeklyPlan::find($id, ['*']);
        if(!$draft) throw new NotFoundError("El draft no existe");
        return $draft; 
    }

    #[Override]
    public function updateDraftWeeklyPlanById(array $data, string $id)
    {
        $draft = $this->getDraftWeeklyPlanById($id);
        $draft->update($data);
        return true;
    }

    #[Override]
    public function deleteDraftWeeklyPlanById(string $id)
    {
        $plan = $this->getDraftWeeklyPlanById($id);
        $plan->tasks()->delete();
        $plan->delete();
        return true;
    }
}

<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Imports\Agricola\WeeklyPlanTasksImport;
use App\Interfaces\Agricola\CdpServiceInterface;
use App\Interfaces\Agricola\SupplyServiceInterface;
use App\Interfaces\Agricola\TaskServiceInterface;
use App\Interfaces\Agricola\WeeklyPlanServiceInterface;
use App\Models\Agricola\WeeklyPlan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Override;

class WeeklyPlanService implements WeeklyPlanServiceInterface
{

    public function __construct(
        private readonly CdpServiceInterface $cdpService,
        private readonly TaskServiceInterface $taskService,
        private readonly SupplyServiceInterface $supplyService,
    ) {}

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
    public function getWeeklyPlans(?string $limit, mixed $user)
    {
        $query = WeeklyPlan::query();
        
        if($user->role == 'auxagricola'){
            $finca = $user->permissions()->first()->permission->name;
            $query->whereHas('finca', function($q) use($finca){
                $q->where('name', 'LIKE', '%'.$finca.'%');
            });
        }
        
        $query->orderBy('created_at', 'DESC');
        if ($limit) return $query->paginate($limit);

        return $query->get();
    }

    #[Override]
    public function getWeeklyPlanById(string $id)
    {
        $weekl_plan = WeeklyPlan::find($id, ['*']);
        if (!$weekl_plan) throw new NotFoundError("El plan semanal no existe");
        return $weekl_plan;
    }

    #[Override]
    public function uploadTasksToWeeklyPlan(mixed $file, string $id)
    {
        $weekly_plan = $this->getWeeklyPlanById($id);
        DB::transaction(function () use ($weekly_plan, $file) {
            Excel::import(new WeeklyPlanTasksImport($this->cdpService, $this->taskService, $this->supplyService, $weekly_plan), $file);
        });
    }

    #[Override]
    public function updateWeeklyPlan(array $data, string $id)
    {
        $weekly_plan = $this->getWeeklyPlanById($id);
        $weekly_plan->update($data);
        $weekly_plan->save();
        return true;
    }
}

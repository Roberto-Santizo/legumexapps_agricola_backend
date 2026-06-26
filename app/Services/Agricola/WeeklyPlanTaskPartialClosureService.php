<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanTaskPartialClosureServiceInterface;
use App\Models\Agricola\WeeklyPlanTaskPartialClosure;
use Carbon\Carbon;
use Override;

class WeeklyPlanTaskPartialClosureService implements WeeklyPlanTaskPartialClosureServiceInterface
{

    #[Override]
    public function addOrEditPartialClosure(array $data)
    {
        $existsPartialClosure = WeeklyPlanTaskPartialClosure::where('task_weekly_plan_id', $data['task_weekly_plan_id'])
            ->where('end_date', null)
            ->first();

        if ($existsPartialClosure) {
            $existsPartialClosure->end_date = Carbon::now();
            $existsPartialClosure->save();
        } else {
            $data['start_date'] = Carbon::now();
            WeeklyPlanTaskPartialClosure::create($data);
        }

        return true;
    }

    #[Override]
    public function createPartialClosure(array $data)
    {
        WeeklyPlanTaskPartialClosure::create($data);
        return true;
    }

    #[Override]
    public function getPartialClosures(?string $taskId)
    {
        if (!$taskId) throw new BadRequestError("El ID de la tarea semanal es requerida");
        $partiaClosures = WeeklyPlanTaskPartialClosure::where('task_weekly_plan_id', $taskId)->get();
        return $partiaClosures;
    }

    #[Override]
    public function getPartialClosureById(string $id)
    {
        $partiaClosure = WeeklyPlanTaskPartialClosure::find($id);
        if (!$partiaClosure) throw new NotFoundError("El cierre parcial no existe");
        return $partiaClosure;
    }

    #[Override]
    public function updatePartialClosureById(string $id, array $data)
    {
        $partiaClosure = $this->getPartialClosureById($id);
        $partiaClosure->update($data);
        return true;
    }

    #[Override]
    public function deletePartialClosureById(string $id)
    {
        $partiaClosure = $this->getPartialClosureById($id);
        $partiaClosure->delete();
        return true;
    }
}

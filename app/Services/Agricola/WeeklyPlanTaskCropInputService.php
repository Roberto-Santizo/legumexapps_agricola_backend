<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanTaskCropInputServiceInterface;
use App\Models\Agricola\WeeklyPlanTaskCropInput;
use Override;

class WeeklyPlanTaskCropInputService implements WeeklyPlanTaskCropInputServiceInterface
{
    #[Override]
    public function createInput(array $data)
    {
        $newInput = WeeklyPlanTaskCropInput::create($data);

        return $newInput;
    }

    #[Override]
    public function getInputs(?string $taskId)
    {
        if (! $taskId) {
            throw new BadRequestError('El ID de la tarea es requerido');
        }
        $inputs = WeeklyPlanTaskCropInput::where('task_crop_weekly_plan_id', $taskId)->get();

        return $inputs;
    }

    #[Override]
    public function getInputById(string $id)
    {
        $input = WeeklyPlanTaskCropInput::find($id);
        if (! $input) {
            throw new NotFoundError('El parametro no existe');
        }

        return $input;
    }

    #[Override]
    public function updateInputById(array $data, string $id)
    {
        $input = $this->getInputById($id);
        $input->update($data);

        return true;
    }

    #[Override]
    public function deleteInputById(string $id)
    {
        $input = $this->getInputById($id);
        $input->delete();

        return true;
    }
}

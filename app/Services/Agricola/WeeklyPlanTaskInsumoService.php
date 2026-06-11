<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\WeeklyPlanTaskInsumoServiceInterface;
use App\Models\Agricola\WeeklyPlanTask;
use App\Models\Agricola\WeeklyPlanTaskInsumo;
use Override;

class WeeklyPlanTaskInsumoService implements WeeklyPlanTaskInsumoServiceInterface
{
    private function _formatData(array $data, WeeklyPlanTask $task): array
    {
        $insumos = [];

        foreach ($data as $insumo) {
            $newInsumo = [
                "insumo_id" => $insumo['insumo_id'],
                "assigned_quantity" => $insumo['assigned_quantity'],
                'task_weekly_plan_id' => $task->id
            ];

            $insumos[] = $newInsumo;
        }

        return $insumos;
    }

    #[Override]
    public function addInsumosToTask(array $data, WeeklyPlanTask $task)
    {
        $insumos = $this->_formatData($data, $task);
        WeeklyPlanTaskInsumo::insert($insumos);
    }

    #[Override]
    public function getInsumoById(string $id)
    {
        $insumo = WeeklyPlanTaskInsumo::find($id, ['*']);
        if (!$insumo) throw new NotFoundError('El insumo no existe');
        return $insumo;
    }

    #[Override]
    public function updateInsumoById(array $data, string $id)
    {
        $insumo = $this->getInsumoById($id);
        $insumo->update($data);
        return $insumo;
    }

    #[Override]
    public function deleteInsumoById(string $id)
    {
        $insumo = $this->getInsumoById($id);
        $insumo->delete();
        return true;
    }
}

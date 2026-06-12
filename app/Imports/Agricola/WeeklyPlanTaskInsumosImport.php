<?php

namespace App\Imports\Agricola;

use App\Errors\BadRequestError;
use App\Interfaces\Agricola\SupplyServiceInterface;
use App\Models\Agricola\WeeklyPlanTaskInsumo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WeeklyPlanTaskInsumosImport implements ToCollection, WithHeadingRow
{
    private array $tasks;

    public function __construct(private readonly SupplyServiceInterface $supplyService, array &$tasks)
    {
        $this->tasks = &$tasks;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $allSupplies = $this->supplyService->getSupplies(null);
        $supplies = $collection->pluck('insumo')->unique()->filter();
        $tasksIds = $collection->pluck('id_tarea')->unique()->filter();

        $this->validateInformation($supplies, $allSupplies, $tasksIds);

        foreach ($collection as $row) {
            if (empty($row['id_tarea'])) continue;
            $supply = $allSupplies->where('code', $row['insumo'])->first();
            $taskId = $this->tasks[$row['id_tarea']];
            $data = [
                'assigned_quantity' => $row['cantidad'],
                'insumo_id' => $supply->id,
                'task_weekly_plan_id' => $taskId
            ];

            WeeklyPlanTaskInsumo::create($data);
        }
    }

    private function validateInformation(mixed $supplies, mixed $allSupplies, mixed $tasksIds)
    {
        $existsMissingSupplies = $supplies->diff($allSupplies->pluck('code'));
        $existsMissingTasksIds = $tasksIds->diff(array_keys($this->tasks));

        if ($existsMissingSupplies->count() > 0) throw new BadRequestError("Algunos insumos no existen, valide la información ingresada");
        if ($existsMissingTasksIds->count() > 0) throw new BadRequestError("Algunos IDS de tareas no estan definidos en la primera hoja");
    }
}

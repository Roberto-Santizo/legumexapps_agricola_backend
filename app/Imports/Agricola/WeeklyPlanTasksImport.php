<?php

namespace App\Imports\Agricola;

use App\Errors\BadRequestError;
use App\Interfaces\Agricola\CdpServiceInterface;
use App\Interfaces\Agricola\SupplyServiceInterface;
use App\Interfaces\Agricola\TaskServiceInterface;
use App\Models\Agricola\WeeklyPlanTask;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class WeeklyPlanTasksImport implements ToCollection, WithHeadingRow, WithMultipleSheets
{
    private $tasks = [];

    public function __construct(
        private readonly CdpServiceInterface $cdpService,
        private readonly TaskServiceInterface $taskService,
        private readonly SupplyServiceInterface $supplyService,
        private mixed $plan
    ) {}

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $cdps = $collection->pluck('cdp')->unique()->filter();
        $tasks = $collection->pluck('tarea')->unique()->filter();

        $allCdps = $this->cdpService->getCdps(null);
        $allTasks = $this->taskService->getTasks(null, null);

        $this->validateInformation($cdps, $tasks, $allCdps, $allTasks);

        foreach ($collection as $row) {
            if (empty($row['cdp'])) continue;

            $cdp = $allCdps->where('name', $row['cdp'])->first();
            $task = $allTasks->where('code', $row['tarea'])->first();
            $newTaskData = [
                'budget' => $row['presupuesto'],
                'hours' => $row['horas'],
                'workers_quantity' => $row['personas'],
                'slots' => $row['personas'],
                'extraordinary' => 0,
                'weekly_plan_id' => $this->plan->id,
                'tarea_id' => $task->id,
                'plantation_control_id' => $cdp->id
            ];

            $task = WeeklyPlanTask::create($newTaskData);
            $this->tasks[$row['id']] = $task->id;
        }
    }

    private function validateInformation(mixed $cdps, mixed $tasks, mixed $allCdps, mixed $allTasks)
    {
        $existsMissingCdps = $cdps->diff($allCdps->pluck('name'));
        $existsMissingTasks = $tasks->diff($allTasks->pluck('code'));

        if ($existsMissingCdps->count() > 0) throw new BadRequestError("Algunos CDPS no existen, valide la información ingresada");
        if ($existsMissingTasks->count() > 0) throw new BadRequestError("Algunas tareas no existe, valide la información ingresada");
    }

    public function sheets(): array
    {
        return [0 => $this, 1 => new WeeklyPlanTaskInsumosImport($this->supplyService, $this->tasks)];
    }
}

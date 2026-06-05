<?php

namespace App\Imports\Agricola;

use App\Errors\BadRequestError;
use App\Interfaces\Agricola\TaskServiceInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TasksImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */

    private TaskServiceInterface $service;

    public function __construct()
    {
        $this->service = app(TaskServiceInterface::class);
    }

    public function collection(Collection $collection)
    {
        $tasks = $this->service->getTasks(null);

        foreach ($collection as $row) {
            $task = $tasks->where('code', $row['codigo'])->first();
            if ($task) throw new BadRequestError("El código {$row['codigo']} ya existe");
            $data = $this->formatData($row);
            $this->service->createTask($data);
        }
    }

    private function formatData(mixed $row)
    {
        return [
            'code' => $row['codigo'],
            'name' => $row['tarea'],
            'description' => $row['descripcion']
        ];
    }
}

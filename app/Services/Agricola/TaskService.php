<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\TaskServiceInterface;
use App\Models\Agricola\Task;

class TaskService implements TaskServiceInterface
{
    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function getTasks(?string $limit)
    {
        $query = Task::query();

        if ($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
    }

    public function getTaskById(string $id)
    {
        $task = Task::where('id', '=', $id, null)->first();

        if (!$task) {
            throw new NotFoundError("La tarea no existe", 404);
        }

        return $task;
    }

    public function updateTaskById(array $data, string $id)
    {
        $this->getTaskById($id);
        $task = Task::where('id', '=', $id, null)->update($data);
        return $task;
    }
}

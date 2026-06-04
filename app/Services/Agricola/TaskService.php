<?php

namespace App\Services\Agricola;

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

        if($limit){
            return $query->paginate($limit);
        }
        
        return $query->get();
    }
}

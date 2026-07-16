<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Imports\Agricola\TasksImport;
use App\Interfaces\Agricola\TaskServiceInterface;
use App\Models\Agricola\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TaskService implements TaskServiceInterface
{
    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function getTasks(?string $limit, ?Request $request)
    {
        $query = Task::query();

        if($request && $request->query('task')){
            $query->where('name', 'LIKE', '%'. $request->query('task') .'%');
        }

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

    public function getTaskByCode(string $code)
    {
        $task = Task::where('code', '=', $code, null)->first();
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

    public function updateTaskByCode(array $data, string $code)
    {
        $this->getTaskByCode($code);
        $task = Task::where('code', '=', $code, null)->update($data);
        return $task;
    }

    public function uploadTasksFromFile(mixed $file)
    {
        DB::transaction(function () use ($file) {
            Excel::import(new TasksImport, $file);
        });
    }
}

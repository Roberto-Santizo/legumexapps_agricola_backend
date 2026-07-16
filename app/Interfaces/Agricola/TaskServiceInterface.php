<?php

namespace App\Interfaces\Agricola;

use Illuminate\Http\Request;

interface TaskServiceInterface
{
    public function createTask(array $data);
    public function getTasks(?string $limit, ?Request $request);
    public function getTaskById(string $id);
    public function getTaskByCode(string $code);
    public function updateTaskById(array $data, string $id);
    public function updateTaskByCode(array $data, string $code);
    public function uploadTasksFromFile(mixed $file);
}

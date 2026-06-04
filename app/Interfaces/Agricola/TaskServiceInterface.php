<?php

namespace App\Interfaces\Agricola;

interface TaskServiceInterface
{
    public function createTask(array $data);
    public function getTasks(string | null $limit);
}

<?php

namespace App\Interfaces\Agricola;

interface TaskGuidelinesServiceInterface
{
    public function createTaskGuideline(array $data);
    public function getTaskGuidelines(?string $limit);
    public function getTaskGuidelineById(string $id);
    public function updateTaskGuidelineById(array $data, string $id);
    public function deleteTaskGuidelineById(string $code);
    public function uploadFile(mixed $file);
}

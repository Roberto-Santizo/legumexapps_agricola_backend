<?php

namespace App\Interfaces\Agricola;

interface CropInputServiceInterface
{
    public function createCropInput(array $data);
    public function getCropInputs(?string $cropId);
    public function getCropInputById(string $id);
    public function updateCropInputById(string $id, array $data);
    public function deleteCropInputById(string $id);
}

<?php

namespace App\Interfaces\Agricola;

interface CropParameterServiceInterface
{
    public function createCropParameter(array $data);
    public function getCropParameters(?string $cropId);
    public function getCropParameterById(string $id);
    public function updateCropParameterById(string $id, array $data);
    public function deleteCropParamaterById(string $id);
}

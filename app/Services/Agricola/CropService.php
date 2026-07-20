<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\CropServiceInterface;
use App\Models\Agricola\Crop;

class CropService implements CropServiceInterface
{
    public function createCrop(array $data)
    {
        $crop = Crop::create($data);
        return $crop;
    }

    public function getCrops(?string $limit)
    {
        $query = Crop::query();
        $query->orderBy('id', 'DESC');

        if ($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
    }

    public function getCropById(string $id)
    {
        $crop = Crop::where('id', '=', $id, null)->first();

        if (!$crop) throw new NotFoundError("El cultivo no existe");

        return $crop;
    }

    public function updateCropById(array $data, string $id)
    {
        $this->getCropById($id);
        $crop = Crop::where('id', '=', $id, null)->update($data);
        return $crop;
    }
}

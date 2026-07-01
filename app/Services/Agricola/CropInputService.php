<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\CropInputServiceInterface;
use App\Models\Agricola\CropInput;
use Override;

class CropInputService implements CropInputServiceInterface
{
    #[Override]
    public function createCropInput(array $data)
    {
        $data['required'] = true;
        $input = CropInput::create($data);
        return $input;
    }

    #[Override]
    public function getCropInputs(?string $cropId)
    {
        if (!$cropId) throw new BadRequestError("El ID del cultivo es requerido");
        $inputs = CropInput::where('crop_id', $cropId)->get(['id', 'key', 'label', 'default_value', 'crop_id']);
        return $inputs;
    }

    #[Override]
    public function getCropInputById(string $id)
    {
        $crop = CropInput::find($id, ['id', 'key', 'label', 'default_value', 'crop_id']);
        if (!$crop) throw new NotFoundError("El input no existe");
        return $crop;
    }

    #[Override]
    public function updateCropInputById(string $id, array $data)
    {
        $crop = $this->getCropInputById($id);
        $data['required'] = true;
        $crop->update($data);
        return true;
    }

    #[Override]
    public function deleteCropInputById(string $id)
    {
        $crop = $this->getCropInputById($id);
        $crop->delete();
        return true;
    }
}

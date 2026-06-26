<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\CropParameterServiceInterface;
use App\Models\Agricola\CropParameter;
use Override;

class CropParameterService implements CropParameterServiceInterface
{
    #[Override]
    public function createCropParameter(array $data)
    {
        $crop = CropParameter::create($data);
        return $crop;
    }

    #[Override]
    public function getCropParameters(?string $cropId)
    {
        if (!$cropId) throw new BadRequestError("El ID del cultivo es necesario");
        $parameters = CropParameter::where('crop_id', $cropId);
        return $parameters;
    }

    #[Override]
    public function getCropParameterById(string $id)
    {
        $cropParameter = CropParameter::find($id, ['*']);
        if (!$cropParameter) throw new NotFoundError("El parametro no existe");
        return $cropParameter;
    }

    #[Override]
    public function updateCropParameterById(string $id, array $data)
    {
        $parameter = $this->getCropParameterById($id);
        $parameter->update($data);
        return true;
    }

    #[Override]
    public function deleteCropParamaterById(string $id)
    {
        $parameter = $this->getCropParameterById($id);
        $parameter->delete();
        return true;
    }
}

<?php

namespace App\Services\Agricola;

use App\Errors\BadRequestError;
use App\Errors\NotFoundError;
use App\Interfaces\Agricola\CropRangesServiceInterface;
use App\Models\Agricola\CropRange;
use Override;

class CropRangeService implements CropRangesServiceInterface
{
    #[Override]
    public function createCropRange(array $data)
    {
        $range = CropRange::create($data);
        return $range;
    }

    #[Override]
    public function getCropRanges(?string $cropId)
    {
        if (!$cropId) throw new BadRequestError("El ID del cultivo es necesario");
        $ranges = CropRange::where('crop_id', $cropId)->get(['id', 'crop_id', 'key', 'max_value', 'min_value', 'result']);
        return $ranges;
    }

    #[Override]
    public function getCropRangeById(string $id)
    {
        $range = CropRange::find($id);
        if(!$range) throw new NotFoundError("El rango no existe", 404);
        return $range;
        
    }

    #[Override]
    public function updateCropRangeById(array $data, string $id)
    {
        $range = $this->getCropRangeById($id);
        $range->update($data);
        return true;
    }

    #[Override]
    public function deleteCropRangeById(string $id)
    {
        $range = $this->getCropRangeById($id);
        $range->delete();
        return true;
    }
}

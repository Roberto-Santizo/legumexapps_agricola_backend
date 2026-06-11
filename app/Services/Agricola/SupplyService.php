<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\SupplyServiceInterface;
use App\Models\Agricola\Supply;
use Override;

class SupplyService implements SupplyServiceInterface
{
    #[Override]
    public function createSupply(array $data)
    {
        $supply = Supply::create($data);
        return $supply;
    }

    #[Override]
    public function getSupplies(?string $limit)
    {
        $query = Supply::query();

        if ($limit) return $query->paginate($limit);

        return $query->get();
    }

    #[Override]
    public function getSupplyById(string $id)
    {
        $supply = Supply::find($id, ['id', 'name', 'code', 'measure']);
        if (!$supply) throw new NotFoundError("Insumo no Encontrado");
        return $supply;
    }

    #[Override]
    public function updateSupplyById(array $data, string $id)
    {
        $this->getSupplyById($id);
        $supply = Supply::where('id', '=', $id)->update($data);
        return $supply;
    }
}

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
        $query->orderBy('id', 'DESC');

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
    public function getSupplyByCode(string $code)
    {
        $supply = Supply::where('code', '=', $code, null)->first();
        if (!$supply) throw new NotFoundError("Insumo no Encontrado");
        return $supply;
    }

    #[Override]
    public function updateSupplyByCode(array $data, string $code)
    {
        $this->getSupplyByCode($code);
        $supply = Supply::where('code', '=', $code, null)->update($data);
        return $supply;
    }
}

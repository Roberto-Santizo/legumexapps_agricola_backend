<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\LoteServiceInterface;
use App\Models\Lote;

class LoteService implements LoteServiceInterface
{
    public function createLote(array $data)
    {
        $lote = Lote::create($data);
        return $lote;
    }

    public function getLotes(?string $limit)
    {
        $query = Lote::query();

        if ($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
    }

    public function getLoteById(string $id)
    {
        $lote = Lote::where('id', '=', $id, null)->first();
        if (!$lote) {
            throw new NotFoundError("El lote no existe");
        }

        return $lote;
    }

    public function updateLoteById(array $data, string $id)
    {
        $this->getLoteById($id);
        $lote = Lote::where('id', '=', $id, null)->update($data);

        return $lote;
    }
}

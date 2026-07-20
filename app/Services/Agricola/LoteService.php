<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\LoteServiceInterface;
use App\Models\Agricola\Lote;
use Override;

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
        $query->orderBy('id', 'DESC');
        
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

    #[Override]
    public function getLoteByCode(string $code)
    {
        $lote = Lote::where('name', '=', $code, null)->first();

        if (!$lote) {
            throw new NotFoundError("El lote no existe");
        }

        return $lote;
    }

    public function updateLoteByCode(array $data, string $code)
    {
        $this->getLoteByCode($code);
        $lote = Lote::where('name', '=', $code, null)->update($data);

        return $lote;
    }
}

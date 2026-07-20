<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\FincaServiceInterface;
use App\Models\Agricola\Finca;
use Override;

class FincaService implements FincaServiceInterface
{
    public function createFinca(array $data)
    {
        return Finca::create($data);
    }

    public function getFincas(string | null $limit)
    {
        $query = Finca::query();
        $query->orderBy('id', 'DESC');

        if ($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
    }

    public function getFincaById(string $id)
    {
        $finca = Finca::find($id, ['id', 'name', 'code', 'terminal_id']);
        if (!$finca) {
            throw new NotFoundError("Finca No Encontrada");
        }
        return $finca;
    }

    #[Override]
    public function getFincaByCode(string $code)
    {
        $finca = Finca::where('code', '=', $code, null)->first(['id', 'name', 'code', 'terminal_id']);
        if (!$finca) {
            throw new NotFoundError("Finca No Encontrada");
        }
        return $finca;
    }

    public function updateFincaByCode(array $data, string $code)
    {
        $this->getFincaByCode($code);
        $finca = Finca::where('code', '=', $code, null)->update($data);
        return $finca;
    }
}

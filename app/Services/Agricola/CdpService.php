<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\CdpServiceInterface;
use App\Models\Agricola\Cdp;
use Override;

class CdpService implements CdpServiceInterface
{
    public function createCdp(array $data)
    {
        $cdp = Cdp::create($data);
        return $cdp;
    }

    public function getCdps(?string $limit)
    {
        $query = Cdp::query();

        $query->with(['lote', 'crop', 'recipe']);

        if ($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
    }

    public function getCdpById(string $id)
    {
        $cdp = Cdp::where('id', '=', $id, null)->first();

        if (!$cdp) throw new NotFoundError("El CDP no existe");

        return $cdp;
    }

    public function getCdpByCode(string $code)
    {
        $cdp = Cdp::where('name', '=', $code, null)->first();

        if (!$cdp) throw new NotFoundError("El CDP no existe");

        return $cdp;
    }

    public function updateCdpById(array $data, string $id)
    {
        $this->getCdpById($id);
        $cdp = Cdp::where('id', '=', $id, null)->update($data);
        return $cdp;
    }

    public function updateCdpByCode(array $data, string $code)
    {
        $this->getCdpByCode($code);
        $cdp = Cdp::where('name', '=', $code, null)->update($data);
        return $cdp;
    }

    #[Override]
    public function explodeCdpTasks(string $code)
    {
        $cdp = $this->getCdpByCode($code);
        return $cdp;
    }
}

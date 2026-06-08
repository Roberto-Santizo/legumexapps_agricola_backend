<?php

namespace App\Interfaces\Agricola;

interface CdpServiceInterface
{
    public function createCdp(array $data);
    public function getCdps(string | null $limit);
    public function getCdpById(string $id);
    public function updateCdpById(array $data, string $id);
}

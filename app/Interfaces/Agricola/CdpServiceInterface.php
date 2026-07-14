<?php

namespace App\Interfaces\Agricola;

interface CdpServiceInterface
{
    public function createCdp(array $data);
    public function getCdps(string | null $limit);
    public function getCdpById(string $id);
    public function getCdpByCode(string $code);
    public function updateCdpById(array $data, string $id);
    public function updateCdpByCode(array $data, string $code);
    public function explodeCdpTasks(string $code);
    public function cleanCdpTasks(string $code);
}

<?php

namespace App\Interfaces\Agricola;

interface AnnualSalaryServiceInterface
{
    public function createAnnualSalary(array $data);
    public function getAnnualSalaries(string | null $limit);
    public function getAnnualSalaryById(string $id);
    public function updateAnnualSalaryById(string $id, array $data);
    public function deleteAnnualSalaryById(string $id);
}

<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\AnnualSalaryServiceInterface;
use App\Models\Agricola\AnnualSalary;
use Override;

class AnnualSalaryService implements AnnualSalaryServiceInterface
{
    #[Override]
    public function createAnnualSalary(array $data)
    {
        $annualSalary = AnnualSalary::create($data);
        return $annualSalary;
    }

    #[Override]
    public function getAnnualSalaries(?string $limit)
    {
        $query = AnnualSalary::query();

        if ($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
    }

    #[Override]
    public function getAnnualSalaryById(string $id)
    {
        $annualSalary = AnnualSalary::find($id);
        if (!$annualSalary) throw new NotFoundError("El salario anual no existe");
        return $annualSalary;
    }

    #[Override]
    public function updateAnnualSalaryById(string $id, array $data)
    {
        $annualSalary = $this->getAnnualSalaryById($id);
        $annualSalary->update($data);
        return true;
    }

    #[Override]
    public function deleteAnnualSalaryById(string $id)
    {
        $annualSalary = $this->getAnnualSalaryById($id);
        $annualSalary->delete();
        return true;
    }
}

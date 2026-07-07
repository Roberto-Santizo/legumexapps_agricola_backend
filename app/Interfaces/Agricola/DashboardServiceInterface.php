<?php

namespace App\Interfaces\Agricola;

interface DashboardServiceInterface
{
    public function summaryTasksByFinca(int $week, int $year);
    public function getActiveTasks(int $week, int $year);
}

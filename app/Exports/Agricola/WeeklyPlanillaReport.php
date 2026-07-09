<?php

namespace App\Exports\Agricola;

use App\Models\Agricola\WeeklyPlan;
use App\Models\Agricola\WeeklyPlanTaskEmployeePayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WeeklyPlanillaReport implements  FromCollection, WithHeadings, WithTitle, WithStyles
{
     /**
     * @return \Illuminate\Support\Collection
     */

    protected WeeklyPlan $plan;
    protected float $septimo = ((3097.21 * 12) / 365) * 1.5;
    protected float $bono =  ((250 * 12) / 365) * 7;

    public function __construct(WeeklyPlan $plan)
    {
        $this->plan = $plan;
    }

    public function collection()
    {
        $rows = collect();
        $employees = $this->plan->payments()->get()->groupBy('code');

        foreach ($employees as $key => $tasks) {
            $hours_tasks = $tasks->where('task_crop_id', null)->sum('theorical_hours');
            $hours_harvest = $tasks->where('task_crop_id', '!=', null)->sum('hours');
            $hours = $hours_harvest + $hours_tasks;
            $amount = $tasks->sum('amount');

            $rows->push([
                'CODIGO' => $key,
                'HORAS SEMANALES' => $hours,
                'MONTO' => $amount,
                'SEPTIMO' => $hours > 44 ? $this->septimo : 0,
                'BONIFICACIÓN' =>  $hours > 44 ? $this->bono : 0,
                'TOTAL A DEVENGAR' => $hours > 44 ? ($amount + $this->septimo + $this->bono) : $amount
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['CODIGO', 'HORAS SEMANALES', 'MONTO', 'SEPTIMO', 'BONIFICACIÓN', 'TOTAL A DENVEGAR'];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => '5564eb']],
        ]);
    }

    public function title(): string
    {
        return 'Detalle Tareas';
    }
}

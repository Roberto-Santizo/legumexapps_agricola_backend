<?php

namespace App\Exports\Agricola;

use App\Models\Agricola\WeeklyPlan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WeeklyPlanPaymentDetails implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected WeeklyPlan $plan;

    public function __construct(WeeklyPlan $plan) {
        $this->plan = $plan;
    }

    public function collection()
    {
        $data = $this->plan->payments->map(function ($payment){
            $cdp    =   $payment->task_plan_id ? $payment->weeklyPlanTask->cdp : $payment->weeklyPlanTaskCrop->cdp;
            $task   =   $payment->task_plan_id ? $payment->weeklyPlanTask->task->name : $payment->weeklyPlanTaskCrop->task->name;
            $lote   =   $cdp->lote->name;

            return [
                'CODIGO' =>                 $payment->code,
                'EMPLEADO' =>               $payment->name,
                'LOTE' =>                   $lote,
                'CDP' =>                    $cdp->name,
                'TAREA REALIZADA' =>        $task,
                'PLAN' =>                   $payment->weeklyPlan->week,
                'MONTO GANADO' =>           $payment->amount,
                'HORAS REALES' =>           $payment->hours,
                'HORAS TEORICAS' =>         $payment->theorical_hours,
                'DIA' =>                    $payment->date->translatedFormat('l'),
            ];
        });

        return collect($data);
    }

    public function headings(): array
    {
        return ['CODIGO', 'EMPLEADO', 'LOTE', 'CDP', 'TAREA REALIZADA', 'PLAN', 'MONTO GANADO', 'HORAS REALES', 'HORAS TEORICAS', 'DIA'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => '5564eb']],
        ]);
    }

    public function title(): string
    {
        return 'Detalle Empleados';
    }
}

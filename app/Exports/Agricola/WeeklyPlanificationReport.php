<?php

namespace App\Exports\Agricola;

use App\Models\Agricola\WeeklyPlan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WeeklyPlanificationReport implements FromCollection, WithHeadings, WithTitle, WithStyles
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
        $data = $this->plan->tasks->map(function($task) {
            $total_hours = $task->end_date ? $task->start_date->diffInHours($task->end_date) : 0;
            return [
                'TAREA' =>              $task->task->name,
                'LOTE' =>               $task->cdp->lote->name,
                'CDP'=>                 $task->cdp->name,
                'GRUPO'=>               $task->group->code,
                'FECHA DE INICIO'=>     $task->start_date ? $task->start_date->format('d-m-Y h:i:s A') : 'SIN FECHA DE INICIO',
                'FECHA DE CIERRE'=>     $task->end_date ? $task->end_date->format('d-m-Y h:i:s A') : 'SIN FECHA DE CIERRE',
                'HORAS REALES' =>       $total_hours,
                'HORAS TEORICAS'=>      $task->hours,
                'FECHA DE OPERACIÓN'=>  $task->operation_date->format('d-m-Y'),
                'ESTADO'=>              $total_hours ? 'CERRADA' : 'SIN CIERRE'  
            ];
        });
        return collect($data);
    }

    public function headings(): array
    {
        return ['TAREA', 'LOTE', 'CDP', 'GRUPO', 'FECHA DE INICIO', 'FECHA DE CIERRE','HORAS REALES', 'HORAS TEORICAS', 'FECHA DE OPERACIÓN', 'ESTADO'];
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
        return 'Detalle Tareas';
    }
}

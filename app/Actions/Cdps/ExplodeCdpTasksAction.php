<?php

namespace App\Actions\Cdps;

use App\Models\Agricola\Cdp;
use App\Models\Agricola\TaskGuideline;

class ExplodeCdpTasksAction
{
    public function execute(Cdp $cdp): void
    {
        $tasks = TaskGuideline::where('finca_id', '=', $cdp->lote->finca_id)->where('recipe_id', $cdp->recipe_id)->where('crop_id', $cdp->crop_id)->get();

        $weeks = range($cdp->start_date->weekOfYear, $cdp->end_date->weekOfYear);

        foreach ($weeks as $index => $week) {
            dd($index + 1);
        }
    }
}

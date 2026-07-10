<?php

namespace App\Imports\Agricola;

use App\Errors\BadRequestError;
use App\Models\Agricola\Crop;
use App\Models\Agricola\Finca;
use App\Models\Agricola\Recipe;
use App\Models\Agricola\Task;
use App\Models\Agricola\TaskGuideline;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TasksGuidelinesImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $taskCodes = $collection->pluck('tarea')->unique()->filter();
        $cropCodes = $collection->pluck('cultivo')->unique()->filter();
        $recipeNames = $collection->pluck('receta')->unique()->filter();
        $fincaCodes = $collection->pluck('finca')->unique()->filter();

        $tasks = Task::select('id', 'code')->whereIn('code', $taskCodes)->get()->keyBy('code');
        $crops = Crop::select('id', 'code')->whereIn('code', $cropCodes)->get()->keyBy('code');
        $recipes = Recipe::select('id', 'name')->whereIn('name', $recipeNames)->get()->keyBy('name');
        $fincas = Finca::select('id', 'code')->whereIn('code', $fincaCodes)->get()->keyBy('code');

        $this->validateInformation($taskCodes, $tasks, $cropCodes, $crops, $recipeNames, $recipes, $fincaCodes, $fincas);

        foreach ($collection as $row) {
            TaskGuideline::create([
                'task_id' => $tasks[$row['tarea']]->id,
                'crop_id' => $crops[$row['cultivo']]->id,
                'finca_id' => $fincas[$row['finca']]->id,
                'recipe_id' => $recipes[$row['receta']]->id,
                'hours_per_size' => $row['horas'],
                'week' => $row['semana']
            ]);
        }
    }

    private function validateInformation(
        Collection $taskCodes,
        Collection $tasks,
        Collection $cropCodes,
        Collection $crops,
        Collection $recipeNames,
        Collection $recipes,
        Collection $fincaCodes,
        Collection $fincas
    ) {
        $missingTasks = $taskCodes->diff($tasks->keys());
        $missingCrops = $cropCodes->diff($crops->keys());
        $missingRecipes = $recipeNames->diff($recipes->keys());
        $missingFincas = $fincaCodes->diff($fincas->keys());

        if ($missingTasks->count() > 0) throw new BadRequestError('Algunas tareas no existen: ' . $missingTasks->implode(', '));
        if ($missingCrops->count() > 0) throw new BadRequestError('Algunos cultivos no existen: ' . $missingCrops->implode(', '));
        if ($missingRecipes->count() > 0) throw new BadRequestError('Algunas recetas no existen: ' . $missingRecipes->implode(', '));
        if ($missingFincas->count() > 0) throw new BadRequestError('Algunas fincas no existen: ' . $missingFincas->implode(', '));
    }
}

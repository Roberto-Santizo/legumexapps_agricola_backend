<?php

namespace App\Http\Controllers\Agricola;

use App\Helpers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agricola\Recipes\CreateRecipeRequest;
use App\Http\Resources\Agricola\PaginatedRecipesResource;
use App\Http\Resources\Agricola\RecipeResource;
use App\Interfaces\Agricola\RecipeServiceInterface;

use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, RecipeServiceInterface $service)
    {
        try {
            $limit = $request->query('limit');
            $recipes = $service->getRecipes($limit);
            $data = $limit ? new PaginatedRecipesResource($recipes) : RecipeResource::collection($recipes);

            return ResponseHandler::success($data, 'Recetas Obtenidas Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRecipeRequest $request, RecipeServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $recipe = $service->createRecipe($data);

            return ResponseHandler::success($recipe, 'Receta Creada Correctamente', 201);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, RecipeServiceInterface $service)
    {
        try {
            $recipe = $service->getRecipeById($id);

            return ResponseHandler::success($recipe, 'Receta Obtenida Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateRecipeRequest $request, string $id, RecipeServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $recipe = $service->updateRecipeById($data, $id);

            return ResponseHandler::success($recipe, 'Receta Actualizada Correctamente', 200);
        } catch (\Throwable $th) {
            return ResponseHandler::error($th);
        }
    }
}

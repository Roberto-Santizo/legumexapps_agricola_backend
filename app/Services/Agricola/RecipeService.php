<?php

namespace App\Services\Agricola;

use App\Errors\NotFoundError;
use App\Interfaces\Agricola\RecipeServiceInterface;
use App\Models\Agricola\Recipe;

class RecipeService implements RecipeServiceInterface
{
    public function createRecipe(array $data)
    {
        $recipe = Recipe::create($data);
        return $recipe;
    }

    public function getRecipes(?string $limit)
    {
        $query = Recipe::query();
        $query->orderBy('id', 'DESC');

        if ($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
    }

    public function getRecipeById(string $id)
    {
        $recipe = Recipe::where('id', '=', $id, null)->first(['id', 'name']);

        if (!$recipe) throw new NotFoundError("Receta no Encontrada");

        return $recipe;
    }

    public function updateRecipeById(array $data, string $id)
    {
        $this->getRecipeById($id);
        $recipe = Recipe::where('id', '=', $id, null)->update($data);
        return $recipe;
    }
}

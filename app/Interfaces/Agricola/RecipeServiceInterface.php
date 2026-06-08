<?php

namespace App\Interfaces\Agricola;

interface RecipeServiceInterface
{
    public function createRecipe(array $data);
    public function getRecipes(string | null $limit);
    public function getRecipeById(string $id);
    public function updateRecipeById(array $data, string $id);
}

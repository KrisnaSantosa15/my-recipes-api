<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRecipeRequest;
use App\Models\Level;
use App\Models\Recipe;
use App\Models\Category;
use App\Http\Resources\RecipeResource;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use Illuminate\Database\Eloquent\Builder;

class RecipeController extends Controller
{
	public function index()
	{
		$recipes = Recipe::with('category', 'level', 'user')->paginate(8);
		return RecipeResource::collection($recipes);
	}

	public function store(StoreRecipeRequest $request)
	{
		$validated = $request->safe()->only('category_id', 'level_id', 'recipe_name', 'image_url', 'ingredients', 'how_to_cook', 'time');

		$isCategoryFound = Category::find($validated['category_id']);
		$isLevelFound = Level::find($validated['level_id']);

		if ($isCategoryFound || $isLevelFound) {
			$validated['user_id'] = auth()->user()->id;
			Recipe::create($validated);
			return response()->json([
				'message' => 'Recipe succescfully saved'
			], 200);
		} else {
			return response()->json([
				'message' => 'Category id or level id not found'
			], 404);
		}
	}

	public function show(Recipe $recipe)
	{
		$recipeDetail = Recipe::where('id', $recipe->id)->with('level', 'category', 'user')->first();
		foreach ($recipeDetail as $recipe) {
			if ($recipeDetail->user_id === auth()->user()->id) {
				$recipeDetail->is_owner = true;
			} else {
				$recipeDetail->is_owner = false;
			}
			if (auth()->user()->favorites()->where('recipe_id', $recipeDetail->id)->exists()) {
				$recipeDetail->is_favorite = true;
			} else {
				$recipeDetail->is_favorite = false;
			}
		}
		return new RecipeResource($recipeDetail);
	}

	public function update(UpdateRecipeRequest $request, Recipe $recipe)
	{
		// ! get old recipe from db where id is the recipe id, then update just only modified value from input request
		$oldRecipe = Recipe::find($recipe->id);
		$validated = $request->safe()->only('category_id', 'user_id', 'level_id', 'recipe_name', 'image_url', 'ingredients', 'how_to_cook', 'time');

		// ! if old recipe id is not the same as the user logged in id, then return unauthorized but if it is the same, then update
		if ($oldRecipe->user_id !== auth()->user()->id) {
			return response()->json([
				'message' => 'Unauthorized'
			], 401);
		} else {
			$validated['category_id'] = empty($validated['category_id']) ? $oldRecipe->category_id : $validated['category_id'];
			$validated['level_id'] = empty($validated['level_id']) ? $oldRecipe->level_id : $validated['level_id'];
			$validated['recipe_name'] = empty($validated['recipe_name']) ? $oldRecipe->recipe_name : $validated['recipe_name'];
			$validated['image_url'] = empty($validated['image_url']) ? $oldRecipe->image_url : $validated['image_url'];
			$validated['ingredients'] = empty($validated['ingredients']) ? $oldRecipe->ingredients : $validated['ingredients'];
			$validated['how_to_cook'] = empty($validated['how_to_cook']) ? $oldRecipe->how_to_cook : $validated['how_to_cook'];
			$validated['time'] = empty($validated['time']) ? $oldRecipe->time : $validated['time'];

			$recipe->update($validated);

			return response()->json([
				'message' => 'Recipe succescfully updated'
			], 200);
		}
	}
	public function destroy(Recipe $recipe)
	{
		// ! if the recipe id is not the same as the user logged in id, then return unauthorized but if it is the same, then delete
		if ($recipe->user_id !== auth()->user()->id) {
			return response()->json([
				'message' => 'Unauthorized'
			], 401);
		} else {
			$recipe->delete();

			return response()->json([
				'message' => 'Recipe succesfully deleted'
			], 200);
		}
	}

	// ! make a function to fetch all recipe belongs to a logged in user
	public function userRecipes()
	{
		$recipes = Recipe::where('user_id', auth()->user()->id)->with('category', 'level', 'user')->paginate(8);
		foreach ($recipes as $recipe) {
			if ($recipe->user_id === auth()->user()->id) {
				$recipe->is_owner = true;
			} else {
				$recipe->is_owner = false;
			}
			if (auth()->user()->favorites()->where('recipe_id', $recipe->id)->exists()) {
				$recipe->is_favorite = true;
			} else {
				$recipe->is_favorite = false;
			}
		}

		return RecipeResource::collection($recipes);
	}

	// search recipe by userRecipes
	public function searchByUserRecipes(SearchRecipeRequest $request)
	{
		$recipe_name = $request->recipe_name;
		$category = $request->category;
		$level = $request->level;
		$user = $request->user;
		$how_to_cook = $request->how_to_cook;
		$ingredients = $request->ingredients;

		$timeBetween = explode("to", $request->timeBetween);

		$from = $timeBetween[0] ?? 1;
		$to = $timeBetween[1] ?? 999999999;

		$recipes = Recipe::with('category', 'level', 'user')
			->where('recipe_name', 'LIKE', '%' . $recipe_name . '%')
			->when($how_to_cook, function ($query) use ($recipe_name) {
				return $query->where('recipe_name', 'LIKE', '%' . $recipe_name . '%')
					->orWhere('how_to_cook', 'LIKE', '%' . $recipe_name . '%');
			})
			->when($ingredients, function ($query) use ($recipe_name) {
				return $query->where('recipe_name', 'LIKE', '%' . $recipe_name . '%')
					->orWhere('ingredients', 'LIKE', '%' . $recipe_name . '%');
			})
			->when($timeBetween ?? null, function ($query) use ($from, $to) {
				return $query->whereBetween('time', [$from, $to]);
			})
			->whereHas('category', function (Builder $query) use ($category) {
				return $query->where('category_name', 'LIKE', '%' . $category . '%');
			})
			->whereHas('level', function (Builder $query) use ($level) {
				return $query->where('name', 'LIKE', '%' . $level . '%');
			})
			->whereHas('user', function (Builder $query) use ($user) {
				return $query->where('full_name', 'LIKE', '%' . $user . '%');
			})
			->where('user_id', auth()->user()->id)
			->paginate(8);

		foreach ($recipes as $recipe) {
			if ($recipe->user_id === auth()->user()->id) {
				$recipe->is_owner = true;
			} else {
				$recipe->is_owner = false;
			}
			if (auth()->user()->favorites()->where('recipe_id', $recipe->id)->exists()) {
				$recipe->is_favorite = true;
			} else {
				$recipe->is_favorite = false;
			}
		}

		return RecipeResource::collection($recipes);
	}

	//search by user favorites
	public function searchByUserFavorites(SearchRecipeRequest $request)
	{
		$recipe_name = $request->recipe_name;
		$category = $request->category;
		$level = $request->level;
		$user = $request->user;
		$how_to_cook = $request->how_to_cook;
		$ingredients = $request->ingredients;

		$timeBetween = explode("to", $request->timeBetween);

		$from = $timeBetween[0] ?? 1;
		$to = $timeBetween[1] ?? 999999999;

		$recipes = auth()->user()->favorites()->with('category', 'level', 'user')
			->where('recipe_name', 'LIKE', '%' . $recipe_name . '%')
			->when($how_to_cook == 'true', function ($query) use ($recipe_name) {
				return $query->where('recipe_name', 'LIKE', '%' . $recipe_name . '%')
					->orWhere('how_to_cook', 'LIKE', '%' . $recipe_name . '%');
			})
			->when($ingredients == 'true', function ($query) use ($recipe_name) {
				return $query->where('recipe_name', 'LIKE', '%' . $recipe_name . '%')
					->orWhere('ingredients', 'LIKE', '%' . $recipe_name . '%');
			})
			->when($request->time, function ($query, $time) {
				return $query->where('time', '>', $time);
			})
			->when($timeBetween ?? null, function ($query) use ($from, $to) {
				return $query->whereBetween('time', [$from, $to]);
			})
			->whereHas('category', function (Builder $query) use ($category) {
				return $query->where('category_name', 'LIKE', '%' . $category . '%');
			})
			->whereHas('level', function (Builder $query) use ($level) {
				return $query->where('name', 'LIKE', '%' . $level . '%');
			})
			->whereHas('user', function (Builder $query) use ($user) {
				return $query->where('full_name', 'LIKE', '%' . $user . '%');
			})
			->whereHas('favorites', function (Builder $query) use ($user) {
				return $query->where('user_id', auth()->user()->id);
			})
			->paginate(8);

		foreach ($recipes as $recipe) {
			if ($recipe->user_id === auth()->user()->id) {
				$recipe->is_owner = true;
			} else {
				$recipe->is_owner = false;
			}
			if (auth()->user()->favorites()->where('recipe_id', $recipe->id)->exists()) {
				$recipe->is_favorite = true;
			} else {
				$recipe->is_favorite = false;
			}
		}

		return RecipeResource::collection($recipes);
	}



	// ! make a function to toggle favorite recipe
	public function toggleFavorite(Recipe $recipe)
	{
		// ! if in table favorites doesn't exist the recipe id and user id, then insert it but if it exist, then toggle it
		if (!auth()->user()->favorites()->where('recipe_id', $recipe->id)->exists()) {
			auth()->user()->favorites()->attach($recipe->id, ['is_favorite' => true]);
			$message = 'Recipe succesfully added to favorites';
		} else {
			auth()->user()->favorites()->detach($recipe->id);
			$message = 'Recipe succesfully removed from favorites';
		}
		return response()->json([
			'message' => $message
		], 200);
	}

	public function userFavorites()
	{
		$favoriteRecipes = auth()->user()->favorites()->with('category', 'level', 'user')->paginate(8);
		foreach ($favoriteRecipes as $recipe) {
			if ($recipe->user_id === auth()->user()->id) {
				$recipe->is_owner = true;
			} else {
				$recipe->is_owner = false;
			}
			if (auth()->user()->favorites()->where('recipe_id', $recipe->id)->exists()) {
				$recipe->is_favorite = true;
			} else {
				$recipe->is_favorite = false;
			}
		}

		return RecipeResource::collection($favoriteRecipes);
	}

	// ! make function for search recipe within keyword and filters (category, level, user)
	public function search(SearchRecipeRequest $request)
	{
		$recipe_name = $request->recipe_name;
		$category = $request->category;
		$level = $request->level;
		$user = $request->user;
		$how_to_cook = $request->how_to_cook;
		$ingredients = $request->ingredients;

		$timeBetween = explode("to", $request->timeBetween);

		$from = $timeBetween[0] ?? 1;
		$to = $timeBetween[1] ?? 999999999;

		$recipes = Recipe::with('level', 'category', 'user')
			->where('recipe_name', 'LIKE', '%' . $recipe_name . '%')
			->when($how_to_cook == 'true', function ($query) use ($recipe_name) {
				return $query->where('recipe_name', 'LIKE', '%' . $recipe_name . '%')
					->orWhere('how_to_cook', 'LIKE', '%' . $recipe_name . '%');
			})
			->when($ingredients == 'true', function ($query) use ($recipe_name) {
				return $query->where('recipe_name', 'LIKE', '%' . $recipe_name . '%')
					->orWhere('ingredients', 'LIKE', '%' . $recipe_name . '%');
			})
			->when($timeBetween ?? null, function ($query) use ($from, $to) {
				return $query->whereBetween('time', [$from, $to]);
			})
			->whereHas('category', function (Builder $query) use ($category) {
				return $query->where('category_name', 'LIKE', '%' . $category . '%');
			})
			->whereHas('level', function (Builder $query) use ($level) {
				return $query->where('name', 'LIKE', '%' . $level . '%');
			})
			->whereHas('user', function (Builder $query) use ($user) {
				return $query->where('full_name', 'LIKE', '%' . $user . '%');
			})
			->paginate(8);

		// ! check if any recipe belongs to user logged in, if yes then add new column is_owner to the response and if any recipe is in favorites, then add new column is_favorite to the response if no then change column is_favorite and is_owner to false
		foreach ($recipes as $recipe) {
			if ($recipe->user_id === auth()->user()->id) {
				$recipe->is_owner = true;
			} else {
				$recipe->is_owner = false;
			}
			if (auth()->user()->favorites()->where('recipe_id', $recipe->id)->exists()) {
				$recipe->is_favorite = true;
			} else {
				$recipe->is_favorite = false;
			}
		}

		return RecipeResource::collection($recipes);
	}
}

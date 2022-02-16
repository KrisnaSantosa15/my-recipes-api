<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
	public function index()
	{
		$categories = Category::paginate(15);
		return CategoryResource::collection($categories);
	}
	public function store(StoreCategoryRequest $request)
	{
		$validated = $request->safe()->only('category_name');

		Category::create($validated);

		return response()->json([
			'message' => 'Category succescfully saved'
		], 200);
	}
	public function show(Category $category)
	{
		return new CategoryResource($category);
	}
	public function update(UpdateCategoryRequest $request, Category $category)
	{
		$validated = $request->safe()->only('category_name');

		$category->update($validated);

		return response()->json([
			'message' => 'Category succescfully updated'
		], 200);
	}
	public function destroy(Category $category)
	{
		$category->delete();

		return response()->json([
			'message' => 'Category succesfully deleted'
		], 200);
	}
}

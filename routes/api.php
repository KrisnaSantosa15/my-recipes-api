<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ! make routes for authentication such as login and regsiter
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ! make resource routes for Category, Level, and Recipe with sanctum middleware
Route::middleware('auth:sanctum')->group(function () {
	// Route::post('search', [RecipeController::class, 'search']);
	Route::get('search', [RecipeController::class, 'search']);
	Route::get('search-my-recipes', [RecipeController::class, 'searchByUserRecipes']);
	Route::get('search-my-favorites', [RecipeController::class, 'searchByUserFavorites']);
	Route::get('recipes/user-recipes', [RecipeController::class, 'userRecipes']);
	Route::get('user-favorites', [RecipeController::class, 'userFavorites']);
	Route::get('toggle-favorite/{recipe}', [RecipeController::class, 'toggleFavorite']);
	Route::resource('categories', CategoryController::class);
	Route::resource('levels', LevelController::class);
	Route::resource('recipes', RecipeController::class);
	Route::post('/logout', [AuthController::class, 'logout']);
});

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
	use HasFactory;

	protected $guarded = [];

	public function favorites()
	{
		return $this->belongsToMany(User::class, 'favorites', 'recipe_id', 'user_id')->withPivot('is_favorite');
	}

	public function level()
	{
		return $this->belongsTo(Level::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}

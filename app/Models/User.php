<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	protected $fillable = [
		'full_name',
		'username',
		'password',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	public function recipes()
	{
		return $this->hasMany(Recipe::class);
	}

	public function favorites()
	{
		return $this->belongsToMany(Recipe::class, 'favorites', 'user_id', 'recipe_id')->withPivot('is_favorite');
	}
}

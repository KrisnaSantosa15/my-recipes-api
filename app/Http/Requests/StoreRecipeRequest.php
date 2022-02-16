<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'category_id' => 'required',
			'level_id' => 'required',
			'recipe_name' => 'required|string',
			'image_url' => 'required|url',
			'ingredients' => 'required|string',
			'how_to_cook' => 'required|string',
			'time' => 'required|integer',
		];
	}
}

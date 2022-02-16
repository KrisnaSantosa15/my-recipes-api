<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
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
			'category_id' => 'nullable',
			'level_id' => 'nullable',
			'recipe_name' => 'string|nullable',
			'image_url' => 'url|nullable',
			'ingredients' => 'string|nullable',
			'how_to_cook' => 'string|nullable',
			'time' => 'integer|nullable',
		];
	}
}

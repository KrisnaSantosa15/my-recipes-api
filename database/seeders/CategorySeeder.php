<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('categories')->insert([
			'category_name' => 'Breakfast',
			'created_at' => date("Y-m-d H:i:s")
		]);

		DB::table('categories')->insert([
			'category_name' => 'Lunch',
			'created_at' => date("Y-m-d H:i:s")
		]);

		DB::table('categories')->insert([
			'category_name' => 'Dinner',
			'created_at' => date("Y-m-d H:i:s")
		]);

		DB::table('categories')->insert([
			'category_name' => 'Supper',
			'created_at' => date("Y-m-d H:i:s")
		]);
	}
}

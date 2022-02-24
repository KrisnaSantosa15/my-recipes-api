<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

		DB::table('levels')->insert([
			'name' => 'Sangat Mudah',
			'created_at' => date("Y-m-d H:i:s")
		]);

		DB::table('levels')->insert([
			'name' => 'Mudah',
			'created_at' => date("Y-m-d H:i:s")
		]);

		DB::table('levels')->insert([
			'name' => 'Sedang',
			'created_at' => date("Y-m-d H:i:s")
		]);

		DB::table('levels')->insert([
			'name' => 'Sulit',
			'created_at' => date("Y-m-d H:i:s")
		]);

		DB::table('levels')->insert([
			'name' => 'Sangat Sulit',
			'created_at' => date("Y-m-d H:i:s")
		]);
	}
}

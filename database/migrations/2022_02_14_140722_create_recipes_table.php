<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recipes', function (Blueprint $table) {
			$table->id();
			$table->foreignId('category_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
			$table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
			$table->foreignId('level_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
			$table->string('recipe_name');
			$table->text('image_url');
			$table->text('ingredients');
			$table->text('how_to_cook');
			$table->integer('time');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('recipes');
	}
};

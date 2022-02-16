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
		Schema::create('favorites', function (Blueprint $table) {
			$table->id();
			$table->foreignId('recipe_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
			$table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
			$table->boolean('is_favorite')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('favorites');
	}
};

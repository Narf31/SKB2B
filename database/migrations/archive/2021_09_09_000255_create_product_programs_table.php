<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductProgramsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_programs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id')->nullable();
			$table->integer('is_actual')->nullable();
			$table->string('title')->nullable();
			$table->string('slug')->nullable();
			$table->text('description', 65535)->nullable();
			$table->integer('sort')->nullable()->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_programs');
	}

}

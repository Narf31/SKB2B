<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBsoLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bso_locations', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->string('title')->nullable();
			$table->integer('is_actual')->default(1);
			$table->integer('can_be_set_manually')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bso_locations');
	}

}

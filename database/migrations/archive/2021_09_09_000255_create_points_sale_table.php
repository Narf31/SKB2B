<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePointsSaleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('points_sale', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->integer('is_actual')->nullable()->default(1);
			$table->integer('city_id')->nullable();
			$table->string('address')->nullable();
			$table->decimal('latitude', 9, 6)->nullable();
			$table->decimal('longitude', 9, 6)->nullable();
			$table->integer('is_sale')->nullable()->default(1);
			$table->integer('is_damages')->nullable()->default(0);
			$table->integer('is_pso')->nullable()->default(0);
			$table->integer('deptIsn')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('points_sale');
	}

}

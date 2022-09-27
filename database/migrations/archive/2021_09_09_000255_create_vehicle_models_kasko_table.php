<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVehicleModelsKaskoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicle_models_kasko', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('CODE')->nullable();
			$table->string('PARENTCODE')->nullable();
			$table->string('NAME')->nullable();
			$table->string('ModelISN')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vehicle_models_kasko');
	}

}

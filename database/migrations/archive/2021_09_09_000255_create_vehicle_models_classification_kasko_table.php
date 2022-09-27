<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVehicleModelsClassificationKaskoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicle_models_classification_kasko', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('CODE')->nullable();
			$table->string('PARENTCODE')->nullable();
			$table->string('NAME')->nullable();
			$table->string('ModelISN')->nullable();
			$table->string('CarCodeAIS1')->nullable();
			$table->string('TypeKey')->nullable();
			$table->string('TypeName')->nullable();
			$table->string('CategoryKey')->nullable();
			$table->string('CategoryName')->nullable();
			$table->string('BodyKey')->nullable();
			$table->string('BodyName')->nullable();
			$table->string('TransmissionKey')->nullable();
			$table->string('TransmissionName')->nullable();
			$table->string('FuelKey')->nullable();
			$table->string('FuelName')->nullable();
			$table->string('PrivodKey')->nullable();
			$table->string('PrivodName')->nullable();
			$table->string('EngVol')->nullable();
			$table->string('EngPwr')->nullable();
			$table->string('CarDoors')->nullable();
			$table->string('CarSeats')->nullable();
			$table->string('MaxWeight')->nullable();
			$table->date('ProdStart')->nullable();
			$table->date('ProdEnd')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vehicle_models_classification_kasko');
	}

}

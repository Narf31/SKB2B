<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsMigrantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_migrants', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->nullable();
			$table->string('address_register')->nullable();
			$table->string('address_register_kladr')->nullable();
			$table->string('address_register_fias_code')->nullable();
			$table->string('address_register_fias_id')->nullable();
			$table->date('date_register')->nullable();
			$table->integer('date_month')->nullable();
			$table->integer('programs_id')->nullable();
			$table->integer('ns')->nullable();
			$table->integer('pregnancy')->nullable();
			$table->integer('clinical_examination')->nullable();
			$table->integer('dental_care')->nullable();
			$table->integer('interment')->nullable();
			$table->integer('transportation')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_migrants');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsPrfTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_prf', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id');
			$table->integer('programs_id')->nullable()->default(1);
			$table->integer('count_day');
			$table->integer('day_to')->nullable();
			$table->integer('amount')->nullable();
			$table->integer('ns_program')->nullable();
			$table->integer('ns_amount')->nullable();
			$table->integer('is_leisure')->nullable()->default(0);
			$table->integer('is_chronic_diseases')->nullable()->default(0);
			$table->integer('is_pregnancy')->nullable()->default(0);
			$table->integer('is_science')->nullable()->default(0);
			$table->integer('is_children')->nullable()->default(0);
			$table->integer('is_alcohol')->nullable()->default(0);
			$table->integer('is_covid19')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_prf');
	}

}

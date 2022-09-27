<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsOsagoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_osago', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->nullable();
			$table->integer('is_epolicy')->nullable()->default(0);
			$table->integer('is_multidriver')->nullable()->default(0);
			$table->date('period_beg1')->nullable();
			$table->date('period_end1')->nullable();
			$table->date('period_beg2')->nullable();
			$table->date('period_end2')->nullable();
			$table->date('period_beg3')->nullable();
			$table->date('period_end3')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_osago');
	}

}

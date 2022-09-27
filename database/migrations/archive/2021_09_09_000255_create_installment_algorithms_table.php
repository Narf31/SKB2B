<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInstallmentAlgorithmsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('installment_algorithms', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('insurance_companies_id')->nullable();
			$table->integer('bso_supplier_id')->nullable();
			$table->integer('product_id')->nullable();
			$table->integer('hold_kv_id')->nullable();
			$table->integer('group_id')->nullable();
			$table->integer('algorithm_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('installment_algorithms');
	}

}

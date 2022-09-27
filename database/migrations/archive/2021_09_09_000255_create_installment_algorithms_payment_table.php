<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInstallmentAlgorithmsPaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('installment_algorithms_payment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->integer('quantity');
			$table->string('details_quantity')->default('');
			$table->integer('is_default')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('installment_algorithms_payment');
	}

}

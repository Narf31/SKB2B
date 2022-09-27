<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInstallmentAlgorithmsPaymentListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('installment_algorithms_payment_list', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('algorithms_payment_id')->nullable();
			$table->decimal('payment', 11)->nullable();
			$table->integer('month')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('installment_algorithms_payment_list');
	}

}

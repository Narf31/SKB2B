<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersDamagesPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders_damages_payments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order_id')->nullable();
			$table->decimal('payment_total', 11)->nullable();
			$table->date('payment_data')->nullable();
			$table->text('comments')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders_damages_payments');
	}

}

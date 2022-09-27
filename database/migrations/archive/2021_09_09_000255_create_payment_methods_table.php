<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentMethodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_methods', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('title');
			$table->integer('is_actual');
			$table->integer('payment_type')->nullable();
			$table->integer('payment_flow')->nullable();
			$table->integer('key_type')->nullable();
			$table->float('acquiring')->nullable();
			$table->integer('file_id')->nullable();
			$table->integer('control_type')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment_methods');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order_id')->nullable();
			$table->dateTime('created_at')->nullable();
			$table->integer('status_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('create_title')->nullable();
			$table->string('status_title')->nullable();
			$table->string('event_title')->nullable();
			$table->string('color')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders_logs');
	}

}

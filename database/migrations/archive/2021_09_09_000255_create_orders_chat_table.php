<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersChatTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders_chat', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order_id')->unsigned();
			$table->integer('sender_id')->unsigned();
			$table->text('text', 65535)->nullable();
			$table->dateTime('date_sent')->nullable();
			$table->dateTime('date_receipt')->nullable();
			$table->boolean('status')->default(0);
			$table->boolean('is_player')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders_chat');
	}

}

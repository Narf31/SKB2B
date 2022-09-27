<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReservationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reservations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->char('inn');
			$table->char('kpp');
			$table->char('payer');
			$table->text('comment', 65535);
			$table->text('address', 65535);
			$table->text('data', 65535);
			$table->timestamps();
			$table->decimal('amount', 11);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reservations');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersBalanceTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_balance_transactions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->dateTime('create_date')->nullable();
			$table->dateTime('event_date')->nullable();
			$table->integer('balance_id')->nullable();
			$table->integer('type_id')->nullable();
			$table->integer('event_type_id')->nullable();
			$table->decimal('total_sum', 11)->nullable();
			$table->decimal('residue', 11)->nullable();
			$table->text('purpose_payment', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_balance_transactions');
	}

}

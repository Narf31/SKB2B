<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCashboxTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cashbox_transactions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->dateTime('event_date')->nullable()->comment('test');
			$table->integer('type_id')->nullable()->default(0);
			$table->integer('event_type_id')->nullable()->default(0);
			$table->integer('cashbox_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->decimal('total_sum', 11)->nullable();
			$table->text('purpose_payment', 65535)->nullable();
			$table->decimal('residue', 11)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cashbox_transactions');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCashboxTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cashbox', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('is_actual')->nullable()->default(1);
			$table->string('title')->nullable();
			$table->integer('user_id')->nullable();
			$table->decimal('balance', 11)->nullable()->default(0.00);
			$table->decimal('max_balance', 11)->nullable()->default(0.00);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cashbox');
	}

}

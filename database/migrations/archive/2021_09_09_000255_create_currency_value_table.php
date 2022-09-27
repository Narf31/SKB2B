<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCurrencyValueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('currency_value', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('currency_id')->nullable();
			$table->date('actual_date')->nullable();
			$table->decimal('amount', 11)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('currency_value');
	}

}

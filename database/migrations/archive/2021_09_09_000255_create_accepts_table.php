<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAcceptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accepts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id');
			$table->integer('payment_id');
			$table->integer('accept_user_id');
			$table->integer('parent_user_id');
			$table->date('accept_date');
			$table->integer('kind_acceptance')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('accepts');
	}

}

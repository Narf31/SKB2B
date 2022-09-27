<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsers2columnsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users2columns', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('column_id');
			$table->integer('user_id');
			$table->integer('orders');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users2columns');
	}

}

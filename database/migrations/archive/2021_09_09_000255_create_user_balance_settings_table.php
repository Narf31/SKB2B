<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserBalanceSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_balance_settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type_id')->nullable()->default(0);
			$table->integer('is_actual')->nullable()->default(1);
			$table->string('title')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_balance_settings');
	}

}

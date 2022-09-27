<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractsLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contracts_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->nullable();
			$table->integer('user_id');
			$table->integer('status_id')->nullable();
			$table->timestamps();
			$table->string('status_title')->nullable();
			$table->text('text', 65535)->nullable();
			$table->text('json')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contracts_logs');
	}

}

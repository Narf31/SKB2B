<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractsChatTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contracts_chat', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->unsigned();
			$table->integer('sender_id')->unsigned();
			$table->text('text', 65535)->nullable();
			$table->dateTime('date_sent')->nullable();
			$table->dateTime('date_receipt')->nullable();
			$table->boolean('status')->default(0);
			$table->boolean('is_player')->default(0);
			$table->integer('type_id')->nullable()->default(0);
			$table->integer('is_file')->nullable()->default(0);
			$table->integer('file_id')->nullable();
			$table->integer('receipt_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contracts_chat');
	}

}

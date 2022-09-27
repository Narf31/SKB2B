<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractsMasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contracts_masks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('file_id')->nullable();
			$table->integer('contract_id')->nullable();
			$table->string('title')->nullable();
			$table->integer('is_payment')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contracts_masks');
	}

}

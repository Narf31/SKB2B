<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractsScansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contracts_scans', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->unsigned();
			$table->string('title');
			$table->string('url');
			$table->timestamps();
			$table->integer('file_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contracts_scans');
	}

}

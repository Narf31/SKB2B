<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractsDocumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contracts_documents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('file_id')->nullable();
			$table->integer('contract_id')->nullable();
			$table->integer('document_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contracts_documents');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractsInsurerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contracts_insurer', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->nullable();
			$table->string('title')->nullable();
			$table->string('title_lat')->nullable();
			$table->date('birthdate')->nullable();
			$table->integer('sex')->nullable()->default(0);
			$table->integer('birthyear')->nullable()->default(0);
			$table->string('phone')->nullable();
			$table->string('email')->nullable();
			$table->integer('doc_type')->nullable();
			$table->string('doc_serie')->nullable();
			$table->string('doc_number')->nullable();
			$table->date('doc_date')->nullable();
			$table->string('doc_info')->nullable();
			$table->integer('citizenship_id')->nullable()->default(51);
			$table->date('exp_date')->nullable();
			$table->integer('expyear')->nullable()->default(0);
			$table->integer('subject_id')->nullable()->default(0);
			$table->integer('general_id')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contracts_insurer');
	}

}

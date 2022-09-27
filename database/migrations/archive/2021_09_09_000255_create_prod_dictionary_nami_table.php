<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProdDictionaryNamiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prod_dictionary_nami', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code')->nullable()->default('');
			$table->integer('category')->nullable();
			$table->string('mark')->nullable();
			$table->string('model')->nullable();
			$table->string('modification')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('prod_dictionary_nami');
	}

}

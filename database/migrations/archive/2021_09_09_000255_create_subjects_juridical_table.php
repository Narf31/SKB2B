<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubjectsJuridicalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subjects_juridical', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->string('ogrn');
			$table->string('inn');
			$table->string('bik');
			$table->string('bank');
			$table->string('rs')->comment('Расчётный счёт');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subjects_juridical');
	}

}

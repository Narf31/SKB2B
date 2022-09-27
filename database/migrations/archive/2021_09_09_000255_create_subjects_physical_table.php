<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubjectsPhysicalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subjects_physical', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('first_name');
			$table->string('second_name');
			$table->string('middle_name');
			$table->string('passport_number');
			$table->string('passport_series');
			$table->integer('is_export')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subjects_physical');
	}

}

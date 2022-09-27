<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralSubjectsFlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_subjects_fl', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('general_subject_id')->nullable();
			$table->date('birthdate')->nullable();
			$table->integer('sex')->nullable()->default(0);
			$table->string('inn')->nullable();
			$table->string('snils')->nullable();
			$table->integer('profession_id')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_subjects_fl');
	}

}

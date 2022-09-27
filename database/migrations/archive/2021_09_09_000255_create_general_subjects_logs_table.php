<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralSubjectsLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_subjects_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('general_subject_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->dateTime('date_sent')->nullable();
			$table->string('text')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_subjects_logs');
	}

}

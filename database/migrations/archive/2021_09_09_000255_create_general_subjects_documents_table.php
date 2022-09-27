<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralSubjectsDocumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_subjects_documents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('general_subject_id')->nullable();
			$table->integer('type_id')->nullable()->default(0);
			$table->string('serie')->nullable();
			$table->string('number')->nullable();
			$table->date('date_issue')->nullable();
			$table->string('unit_code')->nullable();
			$table->string('issued')->nullable();
			$table->integer('is_main')->nullable()->default(0);
			$table->integer('is_actual')->nullable()->default(1);
			$table->date('driver_exp_date')->nullable();
			$table->integer('is_check')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_subjects_documents');
	}

}

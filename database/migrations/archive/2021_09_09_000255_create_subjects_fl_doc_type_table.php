<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubjectsFlDocTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subjects_fl_doc_type', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('isn')->nullable();
			$table->string('title')->nullable();
			$table->integer('sort')->nullable()->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subjects_fl_doc_type');
	}

}

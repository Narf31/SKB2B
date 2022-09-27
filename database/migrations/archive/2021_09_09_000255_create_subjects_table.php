<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subjects', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('type')->comment('0 - physical, 1 - juridical');
			$table->string('title')->nullable()->default('');
			$table->integer('doc_type_id')->nullable()->default(1165);
			$table->string('doc_serie')->nullable();
			$table->string('doc_number')->nullable();
			$table->string('inn')->nullable();
			$table->string('kpp')->nullable();
			$table->string('email')->nullable();
			$table->string('phone')->nullable();
			$table->integer('general_subject_id')->nullable()->default(0);
			$table->integer('is_resident')->nullable()->default(1);
			$table->integer('citizenship_id')->nullable()->default(51);
			$table->integer('user_id')->nullable();
			$table->string('ogrn')->nullable();
			$table->text('comments')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subjects');
	}

}

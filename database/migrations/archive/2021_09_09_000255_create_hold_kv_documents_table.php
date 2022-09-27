<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHoldKvDocumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hold_kv_documents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('hold_kv_id')->nullable();
			$table->string('file_title')->nullable();
			$table->string('file_name')->nullable();
			$table->integer('is_delete')->nullable()->default(0);
			$table->integer('is_required')->nullable()->default(1);
			$table->integer('program_id')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hold_kv_documents');
	}

}

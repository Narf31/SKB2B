<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTableColumnsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('table_columns', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('column_name');
			$table->string('table_key');
			$table->text('column_key', 65535);
			$table->integer('is_as');
			$table->string('as_key');
			$table->integer('sorting')->nullable();
			$table->string('is_summary')->nullable()->default('0');
			$table->integer('is_default')->nullable()->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('table_columns');
	}

}

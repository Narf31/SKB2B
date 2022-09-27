<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralFoundersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_founders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type_id')->nullable();
			$table->integer('general_subject_id')->nullable();
			$table->integer('general_founders_id')->nullable();
			$table->decimal('share', 11)->nullable();
			$table->decimal('share_sum', 11)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_founders');
	}

}

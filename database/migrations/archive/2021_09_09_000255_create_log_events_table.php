<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLogEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('log_events', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->integer('type_id')->nullable();
			$table->integer('object_id')->nullable();
			$table->string('event')->nullable();
			$table->integer('perent_object_id')->nullable();
			$table->integer('root_object_id')->nullable();
			$table->timestamps();
			$table->text('data_map', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('log_events');
	}

}

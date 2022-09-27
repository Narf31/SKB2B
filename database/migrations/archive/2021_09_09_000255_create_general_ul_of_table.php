<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralUlOfTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_ul_of', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('code')->nullable();
			$table->string('full_title')->nullable();
			$table->string('title')->nullable();
			$table->text('hash', 65535)->nullable();
			$table->string('isn')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_ul_of');
	}

}

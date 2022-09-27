<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBsoActsItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bso_acts_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('bso_act_id')->nullable();
			$table->integer('bso_id')->nullable();
			$table->string('bso_title')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bso_acts_items');
	}

}

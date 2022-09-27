<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBsoCartTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bso_cart_types', function(Blueprint $table)
		{
			$table->integer('id')->default(0)->unique('id');
			$table->string('title')->nullable();
			$table->string('short_title')->nullable();
			$table->integer('ordering')->nullable()->default(1);
			$table->integer('published')->default(1);
			$table->integer('act_published')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bso_cart_types');
	}

}

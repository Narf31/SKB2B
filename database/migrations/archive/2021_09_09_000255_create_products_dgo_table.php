<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsDgoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_dgo', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id');
			$table->integer('territory_id');
			$table->integer('is_multidriver')->default(0);
			$table->integer('is_only_spouses')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_dgo');
	}

}

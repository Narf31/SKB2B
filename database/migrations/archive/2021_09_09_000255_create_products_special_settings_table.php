<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsSpecialSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_special_settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id')->nullable();
			$table->text('json')->nullable();
			$table->integer('program_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_special_settings');
	}

}

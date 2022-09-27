<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_info', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable()->default(0);
			$table->integer('product_id')->nullable()->default(0);
			$table->integer('type_id')->nullable()->default(0);
			$table->integer('sort')->nullable()->default(0);
			$table->string('title')->nullable();
			$table->text('info_text', 65535)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_info');
	}

}

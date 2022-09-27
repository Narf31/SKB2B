<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsAgentsSpecialSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_agents_special_settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id')->nullable();
			$table->integer('settings')->nullable()->default(0);
			$table->integer('user_id')->nullable();
			$table->integer('organization_id')->nullable();
			$table->text('json')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_agents_special_settings');
	}

}

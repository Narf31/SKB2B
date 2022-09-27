<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsKaskoDriveTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_kasko_drive', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->nullable();
			$table->integer('is_multidriver')->nullable()->default(0);
			$table->integer('coatings_risks_id')->nullable()->default(1);
			$table->integer('territory_id')->nullable()->default(1);
			$table->integer('repair_options_id')->nullable()->default(1);
			$table->integer('bank_id')->nullable()->default(0);
			$table->integer('is_transition')->nullable()->default(0);
			$table->decimal('official_discount', 11)->nullable();
			$table->decimal('official_discount_total', 11)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_kasko_drive');
	}

}

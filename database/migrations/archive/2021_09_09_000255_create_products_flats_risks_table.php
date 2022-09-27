<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsFlatsRisksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_flats_risks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id')->nullable();
			$table->string('title')->nullable();
			$table->text('insurance_object')->nullable();
			$table->text('risks_events')->nullable();
			$table->text('beneficiary')->nullable();
			$table->text('insurance_territory')->nullable();
			$table->decimal('insurance_amount', 11)->nullable()->default(0.00);
			$table->string('insurance_amount_comment')->nullable();
			$table->decimal('payment_total', 11)->nullable()->default(0.00);
			$table->integer('sort')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_flats_risks');
	}

}

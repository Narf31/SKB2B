<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKaskoProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kasko_product', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id');
			$table->integer('program_id');
			$table->integer('organization_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('kasko_product_id')->nullable();
			$table->decimal('amount', 11)->nullable()->default(0.00);
			$table->decimal('payment_tarife', 11)->nullable()->default(0.00);
			$table->string('amount_text')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kasko_product');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKaskoEquipmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kasko_equipment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id');
			$table->integer('program_id');
			$table->integer('organization_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->decimal('amount_to', 11)->nullable()->default(0.00);
			$table->decimal('amount_from', 11)->nullable()->default(0.00);
			$table->decimal('payment_tarife', 11)->nullable()->default(0.00);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kasko_equipment');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKaskoServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kasko_service', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id');
			$table->integer('program_id');
			$table->integer('organization_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('service_id')->nullable();
			$table->decimal('payment_total', 11)->nullable()->default(0.00);
			$table->string('service_name')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kasko_service');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateObjectEquipmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('object_equipment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id');
			$table->string('title')->nullable();
			$table->decimal('payment_total', 11)->nullable()->default(0.00);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('object_equipment');
	}

}

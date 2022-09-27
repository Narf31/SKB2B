<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHoldKvMatchingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hold_kv_matching', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('insurance_companies_id');
			$table->integer('bso_supplier_id');
			$table->integer('product_id');
			$table->integer('hold_kv_id');
			$table->integer('group_id');
			$table->string('category')->nullable();
			$table->string('type')->nullable();
			$table->string('title')->nullable();
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
		Schema::drop('hold_kv_matching');
	}

}

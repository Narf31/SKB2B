<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHoldKvTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hold_kv', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('insurance_companies_id')->nullable();
			$table->integer('bso_supplier_id')->nullable();
			$table->integer('product_id')->nullable();
			$table->integer('hold_type_id')->nullable();
			$table->integer('is_check_policy')->nullable()->default(0);
			$table->integer('is_many_files')->nullable()->default(0);
			$table->string('many_text')->nullable();
			$table->integer('is_auto_bso')->nullable()->default(0);
			$table->integer('bso_class_id')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hold_kv');
	}

}

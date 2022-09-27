<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinancialGroupPaymentInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('financial_group_payment_info', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('insurance_id')->nullable();
			$table->integer('bso_supplier_id')->nullable();
			$table->integer('hold_kv_id')->nullable();
			$table->integer('group_id')->nullable();
			$table->integer('bso_class_id')->nullable()->default(0);
			$table->integer('hold_type_id')->nullable()->default(0);
			$table->integer('is_auto_bso')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('financial_group_payment_info');
	}

}

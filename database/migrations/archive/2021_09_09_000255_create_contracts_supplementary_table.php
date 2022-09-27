<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractsSupplementaryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contracts_supplementary', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id');
			$table->integer('product_id');
			$table->integer('status_id')->default(0);
			$table->integer('number_id')->default(0);
			$table->string('title')->nullable();
			$table->dateTime('sign_date')->nullable();
			$table->dateTime('begin_date')->nullable();
			$table->dateTime('end_date')->nullable();
			$table->decimal('insurance_amount', 11)->nullable()->default(0.00);
			$table->decimal('payment_total', 11)->nullable()->default(0.00);
			$table->integer('financial_policy_id')->default(0);
			$table->integer('financial_policy_manually_set')->default(0);
			$table->decimal('financial_policy_kv_bordereau', 11)->nullable()->default(0.00);
			$table->decimal('financial_policy_kv_dvoy', 11)->nullable()->default(0.00);
			$table->integer('matching_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contracts_supplementary');
	}

}

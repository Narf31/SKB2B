<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsSupplementaryLiabilityArbitrationManagerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_supplementary_liability_arbitration_manager', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id');
			$table->integer('supplementary_id');
			$table->integer('kv_agent_id')->nullable();
			$table->integer('kv_agent2_id')->nullable();
			$table->integer('kv_manager_id')->nullable();
			$table->integer('kv_manager2_id')->nullable();
			$table->integer('kv_manager3_id')->nullable();
			$table->decimal('kv_agent', 11)->nullable()->default(0.00);
			$table->decimal('kv_agent2', 11)->nullable()->default(0.00);
			$table->decimal('kv_manager', 11)->nullable()->default(0.00);
			$table->decimal('kv_manager2', 11)->nullable()->default(0.00);
			$table->decimal('kv_manager3', 11)->nullable()->default(0.00);
			$table->decimal('base_tariff', 11)->nullable()->default(0.00);
			$table->decimal('manager_tariff', 11)->nullable()->default(0.00);
			$table->decimal('original_tariff', 11)->nullable()->default(0.00);
			$table->decimal('original_payment_total', 11)->nullable()->default(0.00);
			$table->decimal('base_payment_total', 11)->nullable()->default(0.00);
			$table->decimal('manager_payment_total', 11)->nullable()->default(0.00);
			$table->timestamps();
			$table->text('export_data')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_supplementary_liability_arbitration_manager');
	}

}

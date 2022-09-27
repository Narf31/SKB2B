<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsLiabilityArbitrationManagerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_liability_arbitration_manager', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->nullable();
			$table->integer('cro_id')->nullable();
			$table->integer('type_agr_id')->nullable();
			$table->integer('count_current_procedures')->nullable();
			$table->decimal('base_tariff', 11)->nullable();
			$table->decimal('manager_tariff', 11)->nullable();
			$table->decimal('original_tariff', 11)->nullable();
			$table->integer('kv_agent_id')->nullable();
			$table->decimal('kv_agent', 11)->nullable();
			$table->integer('kv_agent2_id')->nullable();
			$table->decimal('kv_agent2', 11)->nullable();
			$table->integer('kv_manager_id')->nullable();
			$table->decimal('kv_manager', 11)->nullable();
			$table->integer('kv_manager2_id')->nullable();
			$table->decimal('kv_manager2', 11)->nullable();
			$table->integer('kv_manager3_id')->nullable();
			$table->decimal('kv_manager3', 11)->nullable();
			$table->decimal('original_payment_total', 11)->nullable();
			$table->decimal('base_payment_total', 11)->nullable();
			$table->decimal('manager_payment_total', 11)->nullable();
			$table->integer('general_insurer_id')->nullable();
			$table->integer('procedure_id')->nullable();
			$table->dateTime('sign_date')->nullable();
			$table->dateTime('begin_date')->nullable();
			$table->dateTime('end_date')->nullable();
			$table->text('export_data')->nullable();
			$table->integer('count_complaints')->nullable();
			$table->integer('count_warnings')->nullable();
			$table->integer('count_fines')->nullable();
			$table->integer('is_urgently')->nullable();
			$table->integer('experience')->nullable();
			$table->text('retroactive_period', 65535)->nullable();
			$table->date('retroactive_period_data')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_liability_arbitration_manager');
	}

}

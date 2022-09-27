<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportsPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reports_payments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id');
			$table->integer('payment_id');
			$table->integer('agent_organization_id')->nullable();
			$table->integer('agent_id')->nullable();
			$table->integer('agent_parent_id')->nullable();
			$table->integer('agent_curator_id')->nullable();
			$table->integer('reports_order_id')->nullable()->default(0);
			$table->integer('reports_dvou_id')->nullable()->default(0);
			$table->string('marker_color')->nullable();
			$table->string('marker_text')->nullable();
			$table->decimal('financial_policy_kv_bordereau', 11)->nullable()->default(0.00);
			$table->decimal('financial_policy_kv_bordereau_total', 11)->nullable()->default(0.00);
			$table->decimal('financial_policy_kv_dvoy', 11)->nullable()->default(0.00);
			$table->decimal('financial_policy_kv_dvoy_total', 11)->nullable()->default(0.00);
			$table->decimal('dep_total', 11)->nullable()->default(0.00);
			$table->decimal('kred_total', 11)->nullable()->default(0.00);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reports_payments');
	}

}

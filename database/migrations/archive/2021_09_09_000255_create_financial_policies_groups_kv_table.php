<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinancialPoliciesGroupsKvTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('financial_policies_groups_kv', function(Blueprint $table)
		{
			$table->integer('financial_policy_id')->unsigned();
			$table->integer('financial_policies_group_id')->unsigned();
			$table->boolean('is_actual')->default(0);
			$table->decimal('kv_sk');
			$table->decimal('kv_parent');
			$table->decimal('kv_borderau')->default(0.00);
			$table->decimal('kv_dvou')->default(0.00);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('financial_policies_groups_kv');
	}

}

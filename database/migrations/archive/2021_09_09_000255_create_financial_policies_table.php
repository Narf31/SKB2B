<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinancialPoliciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('financial_policies', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('bso_supplier_id')->nullable();
			$table->integer('insurance_companies_id')->nullable();
			$table->string('title')->nullable();
			$table->integer('is_actual')->nullable()->default(0);
			$table->date('date_active')->nullable();
			$table->integer('product_id')->nullable();
			$table->decimal('kv_bordereau', 11)->nullable();
			$table->decimal('kv_dvou', 11)->nullable();
			$table->decimal('kv_sk', 11)->nullable();
			$table->decimal('kv_parent', 11)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('financial_policies');
	}

}

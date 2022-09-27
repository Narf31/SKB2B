<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinancialPoliciesSegmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('financial_policies_segments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('insurance_companies_id')->unsigned()->nullable()->default(0);
			$table->integer('bso_supplier_id')->unsigned()->nullable()->default(0);
			$table->integer('financial_policy_id')->unsigned()->nullable();
			$table->integer('insurer_type_id')->unsigned()->nullable();
			$table->integer('location_id')->unsigned()->nullable();
			$table->integer('period')->unsigned()->nullable();
			$table->integer('contract_type_id')->unsigned()->nullable();
			$table->integer('vehicle_country_id')->unsigned()->nullable();
			$table->decimal('vehicle_power_from', 10, 0)->nullable();
			$table->decimal('vehicle_power_to', 10, 0)->nullable();
			$table->integer('vehicle_age')->nullable();
			$table->boolean('has_trailer')->nullable()->default(0);
			$table->boolean('is_multi_drive')->nullable()->default(0);
			$table->integer('drivers_min_age')->unsigned()->nullable();
			$table->integer('drivers_min_exp')->unsigned()->nullable();
			$table->integer('owner_age')->unsigned()->nullable();
			$table->integer('vehicle_category_id')->unsigned()->nullable();
			$table->boolean('period_any')->nullable();
			$table->boolean('contract_type_any')->nullable();
			$table->boolean('vehicle_power_any')->nullable();
			$table->boolean('vehicle_age_any')->nullable()->default(1);
			$table->boolean('has_trailer_any')->nullable();
			$table->boolean('is_multi_drive_any')->nullable();
			$table->boolean('drivers_age_any')->nullable();
			$table->boolean('owner_age_any')->nullable();
			$table->boolean('drivers_exp_any')->nullable();
			$table->boolean('insurer_type_any')->nullable();
			$table->boolean('vehicle_country_any')->nullable();
			$table->boolean('insurer_location_any')->nullable();
			$table->decimal('kbm', 10)->nullable()->default(0.95);
			$table->boolean('kbm_any')->nullable();
			$table->boolean('insurer_kt_any')->nullable()->default(1);
			$table->decimal('insurer_kt', 11)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('financial_policies_segments');
	}

}

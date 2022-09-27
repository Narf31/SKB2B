<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsKaskoStandardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_kasko_standard', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->nullable();
			$table->integer('is_multidriver')->nullable()->default(0);
			$table->integer('coatings_risks_id')->nullable()->default(1);
			$table->integer('territory_id')->nullable()->default(1);
			$table->integer('tenure_id')->nullable()->default(1);
			$table->integer('repair_options_id')->nullable()->default(1);
			$table->integer('franchise_id')->nullable()->default(0);
			$table->integer('franchise_number_id')->nullable();
			$table->integer('is_gap')->nullable()->default(0);
			$table->integer('payment_not_certificates_id')->nullable()->default(0);
			$table->integer('is_emergency_commissioner')->nullable()->default(0);
			$table->integer('is_collection_certificates')->nullable()->default(0);
			$table->integer('is_evacuation')->nullable()->default(0);
			$table->integer('civil_responsibility_sum')->nullable()->default(0);
			$table->text('calc_data')->nullable();
			$table->integer('bank_id')->nullable()->default(0);
			$table->integer('is_transition')->nullable()->default(0);
			$table->decimal('official_discount', 11)->nullable();
			$table->decimal('official_discount_total', 11)->nullable();
			$table->integer('ns_type')->nullable()->default(0);
			$table->integer('ns_count')->nullable()->default(0);
			$table->decimal('ns_sum', 11)->nullable();
			$table->integer('is_auto_credit')->nullable()->default(0);
			$table->integer('limit_indemnity_id')->nullable()->default(1);
			$table->integer('is_only_spouses')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_kasko_standard');
	}

}

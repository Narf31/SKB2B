<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsNsPrisonersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_ns_prisoners', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->nullable();
			$table->string('address_born')->nullable();
			$table->string('address_born_kladr')->nullable();
			$table->string('address_born_fias_code')->nullable();
			$table->string('address_born_fias_id')->nullable();
			$table->string('convicted_under_articles')->nullable();
			$table->string('convicted_term')->nullable();
			$table->integer('convicted_term_contract')->nullable();
			$table->integer('is_chronic_diseases')->nullable();
			$table->string('chronic_diseases')->nullable();
			$table->integer('is_disabilities')->nullable();
			$table->string('disabilities')->nullable();
			$table->integer('insurance_amount_ns')->nullable();
			$table->integer('count_month')->nullable()->default(12);
			$table->integer('is_tuberculosis')->nullable()->default(0);
			$table->integer('insurance_amount_tuberculosis')->nullable()->default(0);
			$table->string('address_location')->nullable();
			$table->string('address_location_kladr')->nullable();
			$table->string('address_location_fias_code')->nullable();
			$table->string('address_location_fias_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_ns_prisoners');
	}

}

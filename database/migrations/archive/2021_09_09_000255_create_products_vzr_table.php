<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsVzrTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_vzr', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->nullable();
			$table->integer('count_day')->nullable();
			$table->integer('type_agr_id')->nullable()->default(1);
			$table->integer('сountry_id')->nullable();
			$table->integer('programs_id')->nullable();
			$table->integer('day_to')->nullable();
			$table->integer('amount')->nullable();
			$table->integer('currency_id')->nullable();
			$table->integer('flight_delay_program')->nullable();
			$table->integer('flight_delay_amount')->nullable();
			$table->integer('missed_flight_program')->nullable();
			$table->integer('missed_flight_amount')->nullable();
			$table->integer('baggage_program')->nullable();
			$table->integer('baggage_amount')->nullable();
			$table->integer('сivil_responsibility_program')->nullable();
			$table->integer('сivil_responsibility_amount')->nullable();
			$table->integer('legal_aid_program')->nullable();
			$table->integer('legal_aid_amount')->nullable();
			$table->integer('cancel_tour_program')->nullable();
			$table->integer('cancel_tour_amount')->nullable();
			$table->integer('ns_program')->nullable();
			$table->integer('ns_amount')->nullable();
			$table->integer('cancel_trip_program')->nullable();
			$table->integer('sport_id')->nullable();
			$table->integer('profession_id')->nullable();
			$table->integer('is_leisure')->nullable();
			$table->integer('is_chronic_diseases')->nullable();
			$table->integer('is_pregnancy')->nullable();
			$table->integer('is_science')->nullable();
			$table->integer('is_children')->nullable();
			$table->integer('is_alcohol')->nullable();
			$table->integer('franchise_id')->nullable();
			$table->integer('is_covid19')->nullable()->default(0);
			$table->string('сountry_json')->nullable();
			$table->integer('is_schengen')->nullable()->default(0);
			$table->text('dates', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_vzr');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubjectsFlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subjects_fl', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('subject_id')->nullable();
			$table->string('fio')->nullable();
			$table->string('fio_lat')->nullable();
			$table->integer('is_resident')->nullable()->default(1);
			$table->integer('sex')->nullable();
			$table->date('birthdate')->nullable();
			$table->string('address_born')->nullable();
			$table->string('address_born_kladr')->nullable();
			$table->string('address_register')->nullable();
			$table->string('address_register_kladr')->nullable();
			$table->string('address_register_okato');
			$table->string('address_register_zip');
			$table->string('address_fact')->nullable();
			$table->string('address_fact_kladr')->nullable();
			$table->string('address_fact_okato');
			$table->string('address_fact_zip');
			$table->integer('doc_type')->nullable()->default(1165);
			$table->string('doc_serie')->nullable();
			$table->string('doc_number')->nullable();
			$table->date('doc_date')->nullable();
			$table->string('doc_office')->nullable();
			$table->string('doc_info')->nullable();
			$table->string('address_register_region');
			$table->string('address_register_city')->nullable();
			$table->string('address_register_city_kladr_id')->nullable();
			$table->string('address_register_street')->nullable();
			$table->string('address_register_house')->nullable();
			$table->string('address_register_block')->nullable();
			$table->string('address_register_flat')->nullable();
			$table->string('address_fact_region');
			$table->string('address_fact_city')->nullable();
			$table->string('address_fact_city_kladr_id')->nullable();
			$table->string('address_fact_street')->nullable();
			$table->string('address_fact_house')->nullable();
			$table->string('address_fact_block')->nullable();
			$table->string('address_fact_flat')->nullable();
			$table->string('address_born_fias_code')->nullable();
			$table->string('address_born_fias_id')->nullable();
			$table->string('address_register_fias_code')->nullable();
			$table->string('address_register_fias_id')->nullable();
			$table->string('address_fact_fias_code')->nullable();
			$table->string('address_fact_fias_id')->nullable();
			$table->integer('citizenship_id')->nullable()->default(51);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subjects_fl');
	}

}

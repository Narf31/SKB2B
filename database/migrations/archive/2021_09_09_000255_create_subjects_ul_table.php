<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubjectsUlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subjects_ul', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('subject_id')->nullable();
			$table->string('manager_fio')->nullable();
			$table->string('manager_position')->nullable();
			$table->date('manager_birthdate')->nullable();
			$table->string('address_register')->nullable();
			$table->string('address_register_kladr')->nullable();
			$table->string('address_register_okato');
			$table->string('address_register_zip');
			$table->string('address_fact')->nullable();
			$table->string('address_fact_kladr')->nullable();
			$table->string('address_fact_okato');
			$table->string('address_fact_zip');
			$table->string('title')->nullable();
			$table->string('inn')->nullable();
			$table->string('kpp')->nullable();
			$table->string('bik')->nullable();
			$table->string('general_manager')->nullable();
			$table->string('ogrn')->nullable();
			$table->string('doc_serie')->nullable();
			$table->string('doc_number')->nullable();
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
			$table->string('address_register_fias_code')->nullable();
			$table->string('address_register_fias_id')->nullable();
			$table->string('address_fact_fias_code')->nullable();
			$table->string('address_fact_fias_id')->nullable();
			$table->integer('bank_id')->nullable();
			$table->string('rs')->nullable();
			$table->string('ks')->nullable();
			$table->integer('doc_type')->nullable();
			$table->string('title_lat')->nullable();
			$table->string('title_full')->nullable();
			$table->string('of_code')->nullable();
			$table->string('of_full_title')->nullable();
			$table->string('of_title')->nullable();
			$table->string('okpo')->nullable();
			$table->string('okato')->nullable();
			$table->string('oktmo')->nullable();
			$table->string('okogy')->nullable();
			$table->string('okved_code')->nullable();
			$table->string('okfs')->nullable();
			$table->string('manager_phone')->nullable();
			$table->string('manager_email')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subjects_ul');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralSubjectsAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_subjects_address', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('general_subject_id')->nullable();
			$table->integer('type_id')->nullable();
			$table->string('address')->nullable();
			$table->string('kladr')->nullable();
			$table->string('fias_code')->nullable();
			$table->string('fias_id')->nullable();
			$table->string('okato')->nullable();
			$table->string('zip')->nullable();
			$table->string('region')->nullable();
			$table->string('city')->nullable();
			$table->string('city_kladr_id')->nullable();
			$table->string('street')->nullable();
			$table->string('house')->nullable();
			$table->string('block')->nullable();
			$table->string('flat')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_subjects_address');
	}

}

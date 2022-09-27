<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInsuranceCompaniesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('insurance_companies', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->boolean('is_actual')->nullable()->default(0);
			$table->integer('logo_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('insurance_companies');
	}

}

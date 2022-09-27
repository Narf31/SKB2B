<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBsoDopSerieTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bso_dop_serie', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type_bso_id')->nullable();
			$table->integer('insurance_companies_id')->nullable();
			$table->integer('bso_serie_id')->nullable();
			$table->string('bso_dop_serie')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bso_dop_serie');
	}

}

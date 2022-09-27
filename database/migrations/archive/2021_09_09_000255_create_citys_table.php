<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCitysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('citys', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->integer('is_actual')->nullable()->default(1);
			$table->string('kladr');
			$table->string('geo_lat')->nullable();
			$table->string('geo_lon')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('citys');
	}

}

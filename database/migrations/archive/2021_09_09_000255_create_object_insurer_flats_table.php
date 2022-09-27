<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateObjectInsurerFlatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('object_insurer_flats', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('object_insurer_id')->nullable()->default(0);
			$table->string('address')->nullable();
			$table->string('address_kladr')->nullable();
			$table->string('address_region')->nullable();
			$table->string('address_city')->nullable();
			$table->string('address_city_kladr_id')->nullable();
			$table->string('address_street')->nullable();
			$table->string('address_house')->nullable();
			$table->string('address_block')->nullable();
			$table->string('address_flat')->nullable();
			$table->string('address_latitude')->nullable();
			$table->string('address_longitude')->nullable();
			$table->integer('house_floor')->nullable();
			$table->integer('flat_floor')->nullable();
			$table->text('comments', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('object_insurer_flats');
	}

}

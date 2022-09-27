<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontLidsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('front_lids', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('front_key');
			$table->string('FIO');
			$table->string('Phones');
			$table->string('Date');
			$table->string('Email');
			$table->string('Addres');
			$table->string('Source');
			$table->string('KindOfInsurance');
			$table->string('Marka');
			$table->string('Model');
			$table->string('YearObject');
			$table->text('HtmlForm', 65535);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('front_lids');
	}

}

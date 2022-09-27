<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBsoSerieTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bso_serie', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type_bso_id')->nullable();
			$table->integer('insurance_companies_id')->nullable();
			$table->integer('bso_class_id')->nullable();
			$table->integer('product_id')->nullable();
			$table->string('bso_serie')->nullable();
			$table->integer('bso_count_number')->nullable();
			$table->integer('is_actual')->nullable()->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bso_serie');
	}

}

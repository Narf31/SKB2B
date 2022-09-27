<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTypeBsoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('type_bso', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('insurance_companies_id')->nullable();
			$table->integer('product_id')->nullable();
			$table->integer('is_actual')->nullable()->default(0);
			$table->integer('min_yellow')->nullable()->default(0);
			$table->integer('min_red')->nullable()->default(0);
			$table->integer('day_sk')->nullable()->default(0);
			$table->integer('day_agent')->nullable()->default(0);
			$table->string('title')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('type_bso');
	}

}

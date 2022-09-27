<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDgoBaserateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dgo_baserate', function(Blueprint $table)
		{
			$table->increments('id');
			$table->decimal('insurance_amount', 11)->nullable()->default(0.00);
			$table->decimal('payment_total', 11)->nullable()->default(0.00);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dgo_baserate');
	}

}

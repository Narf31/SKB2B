<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGapBaserateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gap_baserate', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('program_id');
			$table->integer('conf_key');
			$table->decimal('amount_from', 11)->nullable()->default(0.00);
			$table->decimal('amount_to', 11)->nullable()->default(0.00);
			$table->decimal('max_amount', 11)->nullable()->default(0.00);
			$table->decimal('net_premium', 11)->nullable()->default(0.00);
			$table->decimal('marketing_kv', 11)->nullable()->default(0.00);
			$table->decimal('technical_payment', 11)->nullable()->default(0.00);
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
		Schema::drop('gap_baserate');
	}

}

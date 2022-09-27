<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKaskoBaserateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kasko_baserate', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id');
			$table->integer('program_id');
			$table->integer('organization_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('mark_id')->nullable();
			$table->integer('model_id')->nullable();
			$table->integer('year')->nullable();
			$table->decimal('payment_damage', 11)->nullable();
			$table->decimal('total', 11)->nullable();
			$table->decimal('theft', 11)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kasko_baserate');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBsoCartsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bso_carts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id_to')->default(0);
			$table->integer('bso_cart_type')->default(0);
			$table->integer('bso_state_id')->default(0);
			$table->integer('bso_manager_id')->default(0);
			$table->integer('tp_id')->default(0);
			$table->integer('tp_new_id')->default(0);
			$table->integer('tp_bso_manager_id')->default(0);
			$table->dateTime('time_create')->nullable();
			$table->integer('cart_state_id')->default(0);
			$table->integer('bso_order_id')->default(0);
			$table->integer('state_order')->nullable()->default(0)->comment('1 обеденен');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bso_carts');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBsoActsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bso_acts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->dateTime('time_create')->nullable();
			$table->integer('type_id')->default(0);
			$table->integer('user_id_from')->default(0);
			$table->integer('user_id_to')->default(0);
			$table->integer('bso_manager_id')->default(0);
			$table->integer('location_from')->default(0);
			$table->integer('location_to')->default(0);
			$table->integer('bso_state_id')->default(0);
			$table->string('act_number')->nullable();
			$table->integer('act_number_int')->unsigned()->default(0);
			$table->integer('curr_tp_id')->default(1);
			$table->integer('tp_id')->default(0);
			$table->integer('courier_id')->default(0);
			$table->integer('courier_state_id')->default(0);
			$table->dateTime('courier_target_date')->nullable();
			$table->dateTime('target_date')->nullable();
			$table->integer('realized_state_id')->default(0);
			$table->dateTime('time_close')->nullable();
			$table->integer('bso_cart_id')->default(0);
			$table->string('act_name')->nullable();
			$table->integer('act_org_id')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bso_acts');
	}

}

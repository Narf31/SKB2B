<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBsoLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bso_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('bso_id')->default(0);
			$table->integer('bso_act_id')->default(0);
			$table->integer('bso_state_id')->default(0);
			$table->integer('bso_location_id')->default(0);
			$table->integer('bso_user_from')->default(0);
			$table->integer('bso_user_to')->default(0);
			$table->integer('user_id')->default(0);
			$table->string('ip_address')->nullable();
			$table->integer('contract_id')->default(0);
			$table->integer('reports_act_id')->default(0);
			$table->integer('reports_order_id')->default(0);
			$table->integer('is_deleted')->default(0);
			$table->integer('cashbox_id')->default(0);
			$table->dateTime('log_time');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bso_logs');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBsoTransfersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bso_transfers', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('type_id');
			$table->integer('status_id');
			$table->integer('user_id_to');
			$table->integer('user_id_from');
			$table->integer('point_sale_id');
			$table->integer('bso_manager_id');
			$table->integer('courier_id');
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
		Schema::drop('bso_transfers');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRolesVisibilityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles_visibility', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('role_id')->unsigned();
			$table->integer('permission_id')->unsigned();
			$table->integer('visibility')->default(-1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('roles_visibility');
	}

}

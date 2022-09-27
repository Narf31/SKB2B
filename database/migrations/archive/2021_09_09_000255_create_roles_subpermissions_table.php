<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRolesSubpermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles_subpermissions', function(Blueprint $table)
		{
			$table->integer('subpermission_id');
			$table->integer('role_id');
			$table->integer('view')->default(0);
			$table->integer('edit')->default(0);
			$table->integer('permission_id')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('roles_subpermissions');
	}

}

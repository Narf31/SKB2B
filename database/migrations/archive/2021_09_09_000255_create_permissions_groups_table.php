<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionsGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permissions_groups', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->integer('sort_view')->nullable()->default(0);
			$table->integer('is_visibility')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('permissions_groups');
	}

}

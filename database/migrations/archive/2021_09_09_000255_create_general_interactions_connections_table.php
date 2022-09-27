<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralInteractionsConnectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_interactions_connections', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type_id')->nullable();
			$table->integer('general_subject_id')->nullable();
			$table->integer('general_organization_id')->nullable();
			$table->string('job_position')->nullable();
			$table->date('date_from')->nullable();
			$table->date('date_to')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_interactions_connections');
	}

}

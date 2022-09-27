<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractsScoringsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contracts_scorings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type_id');
			$table->integer('contract_id');
			$table->integer('state_id')->nullable()->default(0);
			$table->integer('is_actual')->nullable()->default(0);
			$table->string('title')->nullable();
			$table->string('query_type_id')->nullable();
			$table->string('query')->nullable();
			$table->text('json_send')->nullable();
			$table->text('json_response')->nullable();
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
		Schema::drop('contracts_scorings');
	}

}

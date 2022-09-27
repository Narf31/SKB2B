<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractsCalculationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contracts_calculations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id');
			$table->string('sum');
			$table->text('messages', 65535);
			$table->text('json');
			$table->timestamps();
			$table->text('risks');
			$table->integer('state_calc')->default(0);
			$table->integer('matching_id')->nullable();
			$table->integer('sk_key_id')->nullable();
			$table->integer('is_actual')->nullable()->default(1);
			$table->integer('program_id')->nullable();
			$table->decimal('insurance_amount', 11)->nullable()->default(0.00);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contracts_calculations');
	}

}

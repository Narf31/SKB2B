<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrgBankAccountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('org_bank_account', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('org_id')->nullable();
			$table->string('account_number')->nullable();
			$table->integer('bank_id')->nullable()->default(0);
			$table->decimal('non_cash', 11)->nullable()->default(0.00);
			$table->string('bik')->nullable();
			$table->integer('is_actual')->nullable()->default(1);
			$table->string('kur')->nullable();
			$table->string('bank_title')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('org_bank_account');
	}

}

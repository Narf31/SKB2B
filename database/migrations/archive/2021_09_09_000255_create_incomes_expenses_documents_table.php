<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIncomesExpensesDocumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('incomes_expenses_documents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('file_id');
			$table->integer('income_id')->index();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('incomes_expenses_documents');
	}

}

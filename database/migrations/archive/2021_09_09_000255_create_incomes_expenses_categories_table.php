<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIncomesExpensesCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('incomes_expenses_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->default('');
			$table->integer('is_actual');
			$table->integer('type');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('incomes_expenses_categories');
	}

}

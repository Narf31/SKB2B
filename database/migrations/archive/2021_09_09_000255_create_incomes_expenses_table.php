<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIncomesExpensesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('incomes_expenses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id');
			$table->integer('status_id');
			$table->integer('user_id');
			$table->dateTime('date');
			$table->decimal('sum', 11)->nullable();
			$table->decimal('commission', 11)->nullable();
			$table->decimal('total', 11)->nullable();
			$table->text('comment', 65535);
			$table->integer('payment_type')->default(0);
			$table->dateTime('payment_date')->nullable();
			$table->integer('payment_user_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('incomes_expenses');
	}

}

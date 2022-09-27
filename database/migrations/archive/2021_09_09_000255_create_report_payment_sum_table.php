<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportPaymentSumTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('report_payment_sum', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('type_id')->default(0);
			$table->decimal('amount', 11)->nullable()->default(0.00);
			$table->integer('user_id');
			$table->integer('report_id');
			$table->text('comments', 65535);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('report_payment_sum');
	}

}

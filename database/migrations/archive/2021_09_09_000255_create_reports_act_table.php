<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportsActTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reports_act', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('title');
			$table->char('signatory_org');
			$table->char('signatory_sk_bso_supplier');
			$table->integer('bso_supplier_id')->default(0);
			$table->integer('report_year')->default(0);
			$table->integer('report_month')->default(0);
			$table->integer('type_id')->default(0);
			$table->integer('accept_status')->default(0);
			$table->integer('is_deleted')->default(0);
			$table->integer('accept_user_id')->default(0);
			$table->integer('create_user_id')->default(0);
			$table->date('accepted_at');
			$table->date('report_date_start');
			$table->date('report_date_end');
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
		Schema::drop('reports_act');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportsOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reports_orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('bso_supplier_id')->default(0);
			$table->integer('agent_organization_id')->default(0);
			$table->integer('type_id')->default(0);
			$table->integer('accept_status')->default(0);
			$table->integer('create_user_id')->nullable();
			$table->string('title')->nullable();
			$table->string('signatory_org')->nullable();
			$table->string('signatory_sk_bso_supplier')->nullable();
			$table->integer('report_year')->nullable()->default(0);
			$table->integer('report_month')->nullable()->default(0);
			$table->integer('is_deleted')->nullable()->default(0);
			$table->date('report_date_start');
			$table->date('report_date_end');
			$table->dateTime('accepted_at')->nullable();
			$table->integer('accept_user_id')->nullable();
			$table->decimal('payment_total', 11)->nullable();
			$table->decimal('bordereau_total', 11)->nullable();
			$table->decimal('dvoy_total', 11)->nullable();
			$table->decimal('amount_total', 11)->nullable();
			$table->decimal('to_transfer_total', 11)->nullable();
			$table->decimal('to_return_total', 11)->nullable();
			$table->text('comments', 65535);
			$table->timestamps();
			$table->decimal('advance_payment', 11)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reports_orders');
	}

}

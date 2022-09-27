<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoices', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->timestamps();
			$table->integer('status_id');
			$table->string('type');
			$table->integer('create_type')->comment('Тип счёта при создании');
			$table->integer('org_id');
			$table->integer('agent_id')->nullable();
			$table->integer('type_invoice_payment_id')->nullable();
			$table->integer('invoice_payment_balance_id')->nullable();
			$table->decimal('invoice_payment_total', 11)->nullable();
			$table->text('invoice_payment_com', 65535)->nullable();
			$table->integer('invoice_payment_user_id')->nullable();
			$table->dateTime('invoice_payment_date')->nullable();
			$table->integer('file_id')->nullable();
			$table->integer('payment_method_id')->nullable();
			$table->text('md5_token', 65535)->nullable();
			$table->text('payment_linck_id', 65535)->nullable();
			$table->text('payment_linck', 65535)->nullable();
			$table->integer('kkt_status_id')->nullable()->default(0);
			$table->text('kkt_token', 65535)->nullable();
			$table->text('kkt_json', 65535)->nullable();
			$table->string('client_email')->nullable();
			$table->string('client_info')->nullable();
			$table->integer('client_type')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoices');
	}

}

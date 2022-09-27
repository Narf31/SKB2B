<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('statys_id')->nullable()->default(0);
			$table->integer('type_id')->nullable()->default(0);
			$table->integer('is_deleted')->nullable()->default(0);
			$table->integer('invoice_id')->nullable();
			$table->integer('bso_id')->nullable();
			$table->integer('contract_id')->nullable();
			$table->integer('payment_number')->nullable();
			$table->date('payment_data')->nullable();
			$table->integer('payment_type')->nullable()->default(0);
			$table->integer('payment_flow')->nullable()->default(0);
			$table->decimal('payment_total', 11)->nullable();
			$table->decimal('official_discount', 11)->nullable();
			$table->decimal('official_discount_total', 11)->nullable();
			$table->decimal('informal_discount', 11)->nullable();
			$table->decimal('informal_discount_total', 11)->nullable();
			$table->decimal('bank_kv', 11)->nullable();
			$table->decimal('bank_kv_total', 11)->nullable();
			$table->integer('financial_policy_id')->nullable();
			$table->integer('financial_policy_manually_set')->nullable()->default(0);
			$table->decimal('financial_policy_kv_bordereau', 11)->nullable();
			$table->decimal('financial_policy_kv_bordereau_total', 11)->nullable();
			$table->decimal('financial_policy_kv_dvoy', 11)->nullable();
			$table->decimal('financial_policy_kv_dvoy_total', 11)->nullable();
			$table->decimal('financial_policy_kv_parent', 11)->nullable();
			$table->decimal('financial_policy_kv_parent_total', 11)->nullable();
			$table->integer('bso_not_receipt')->nullable()->default(0);
			$table->string('bso_receipt')->nullable();
			$table->integer('bso_receipt_id')->nullable();
			$table->integer('agent_organization_id')->nullable();
			$table->integer('agent_id')->nullable();
			$table->integer('agent_parent_id')->nullable();
			$table->integer('agent_curator_id')->nullable();
			$table->integer('manager_id')->nullable();
			$table->integer('realized_act_id')->nullable()->default(0);
			$table->decimal('invoice_payment_total', 11)->nullable();
			$table->dateTime('invoice_payment_date')->nullable();
			$table->text('comments', 65535)->nullable();
			$table->integer('point_sale_id')->nullable();
			$table->integer('set_balance')->nullable()->default(0);
			$table->integer('user_id')->nullable();
			$table->integer('order_id')->nullable();
			$table->string('order_title')->nullable();
			$table->integer('reports_order_id')->nullable()->default(0);
			$table->integer('reports_dvou_id')->nullable()->default(0);
			$table->string('marker_color')->nullable();
			$table->string('marker_text')->nullable();
			$table->integer('acts_sk_id')->default(0);
			$table->integer('accept_user_id');
			$table->date('accept_date')->nullable();
			$table->integer('payment_method_id')->nullable();
			$table->decimal('installment_algorithms_payment', 11)->nullable();
			$table->decimal('acquire_percent', 11)->nullable();
			$table->decimal('acquire_total', 11)->nullable();
			$table->decimal('financial_policy_marjing', 11)->nullable();
			$table->decimal('financial_policy_marjing_total', 11)->nullable();
			$table->integer('payment_type_send_checkbox')->nullable();
			$table->string('payment_send_checkbox')->nullable();
			$table->string('send_email')->nullable();
			$table->integer('supplementary_id')->nullable();
			$table->integer('is_export')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payments');
	}

}

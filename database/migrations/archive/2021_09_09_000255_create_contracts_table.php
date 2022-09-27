<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contracts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('statys_id')->nullable()->default(0);
			$table->integer('user_id')->nullable();
			$table->integer('bso_id')->nullable();
			$table->string('bso_title')->nullable();
			$table->integer('bso_supplier_id')->nullable();
			$table->integer('product_id')->nullable();
			$table->integer('program_id')->nullable();
			$table->integer('agent_id')->nullable();
			$table->integer('agent_organization_id')->nullable();
			$table->integer('agent_parent_id')->nullable();
			$table->integer('agent_curator_id')->nullable();
			$table->integer('is_personal_sales')->nullable()->default(0);
			$table->integer('manager_id')->nullable()->default(0);
			$table->integer('sales_condition')->nullable()->default(0);
			$table->dateTime('sign_date')->nullable();
			$table->dateTime('begin_date')->nullable();
			$table->dateTime('end_date')->nullable();
			$table->integer('insurer_id')->nullable();
			$table->integer('object_insurer_id')->nullable();
			$table->decimal('payment_total', 11)->nullable();
			$table->integer('financial_policy_manually_set')->nullable()->default(0);
			$table->integer('financial_policy_id')->nullable();
			$table->decimal('financial_policy_kv_bordereau', 11)->nullable();
			$table->decimal('financial_policy_kv_dvoy', 11)->nullable();
			$table->integer('installment_algorithms_id')->nullable();
			$table->text('error_accept', 65535)->nullable();
			$table->integer('check_user_id')->nullable();
			$table->integer('kind_acceptance')->nullable()->default(0);
			$table->boolean('is_online')->nullable();
			$table->integer('type_id')->default(1);
			$table->decimal('insurance_amount', 11)->default(0.00);
			$table->string('tarif_description')->nullable();
			$table->dateTime('accept_date')->nullable();
			$table->integer('is_prolongation')->nullable()->default(0);
			$table->integer('prolongation_bso_id')->nullable();
			$table->string('prolongation_bso_title')->nullable();
			$table->integer('owner_id')->nullable();
			$table->integer('beneficiar_id')->nullable();
			$table->text('md5_token', 65535)->nullable();
			$table->integer('sk_contract_id')->nullable();
			$table->string('sk_contract_title')->nullable();
			$table->integer('matching_num')->nullable()->default(0);
			$table->integer('matching_underwriter_id')->nullable();
			$table->integer('matching_sb_id')->nullable();
			$table->integer('matching_inspection_id')->nullable();
			$table->integer('scoring_state')->nullable()->default(0);
			$table->text('scoring_text')->nullable();
			$table->integer('is_all_docs_exist')->nullable()->default(0);
			$table->decimal('financial_policy_kv_parent', 11)->nullable()->default(0.00);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contracts');
	}

}

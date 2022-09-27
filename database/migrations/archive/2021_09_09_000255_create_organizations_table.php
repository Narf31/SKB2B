<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrganizationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('organizations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->integer('next_act')->nullable()->default(0);
			$table->string('default_purpose_payment')->nullable();
			$table->string('inn')->nullable();
			$table->decimal('limit_year', 11)->nullable()->default(0.00);
			$table->decimal('spent_limit_year', 11)->nullable()->default(0.00);
			$table->integer('is_actual')->nullable()->default(1);
			$table->integer('org_type_id')->nullable();
			$table->string('title_doc')->nullable();
			$table->string('general_manager')->nullable();
			$table->string('address')->nullable();
			$table->string('phone')->nullable();
			$table->string('email')->nullable();
			$table->text('comment', 65535)->nullable();
			$table->integer('status_security_service')->nullable()->default(0);
			$table->integer('parent_user_id')->nullable();
			$table->string('kpp')->nullable();
			$table->string('fact_address')->nullable();
			$table->string('user_contact_title')->nullable();
			$table->integer('is_delete')->nullable()->default(0);
			$table->integer('parent_org_id')->nullable();
			$table->integer('is_main_company')->nullable()->default(0);
			$table->string('agent_contract_title')->nullable();
			$table->date('agent_contract_begin_date')->nullable();
			$table->date('agent_contract_end_date')->nullable();
			$table->integer('curator_id')->nullable();
			$table->integer('financial_group_id')->nullable()->default(0);
			$table->integer('ban_level')->nullable()->default(0);
			$table->string('ban_reason')->nullable();
			$table->text('products_sale')->nullable();
			$table->string('payment_type_agent')->nullable();
			$table->string('api_key')->nullable();
			$table->string('secret_key')->nullable();
			$table->integer('frontnodeisn')->nullable()->default(0);
			$table->integer('subjisn')->nullable()->default(0);
			$table->integer('points_sale_id')->nullable()->default(0);
			$table->string('code_partner')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('organizations');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('is_actual');
			$table->string('title');
			$table->boolean('financial_policy_type_id')->default(0);
			$table->integer('category_id')->nullable()->default(1);
			$table->string('slug')->nullable();
			$table->boolean('is_online')->nullable();
			$table->text('description')->nullable();
			$table->decimal('insurance_amount_default', 11)->default(0.00);
			$table->decimal('underwriter_rate', 11)->default(0.00);
			$table->integer('kv_official_available');
			$table->integer('kv_informal_available');
			$table->integer('kv_bank_available');
			$table->integer('for_inspections')->nullable();
			$table->string('inspection_temple_act')->nullable();
			$table->integer('template_id')->nullable();
			$table->integer('is_dvou')->nullable()->default(0);
			$table->integer('template_contract_id')->nullable();
			$table->integer('is_common_calculation')->nullable()->default(0);
			$table->string('template_print')->nullable();
			$table->string('template_print_x')->nullable();
			$table->string('template_print_y')->nullable();
			$table->string('template_signature')->nullable();
			$table->string('template_signature_x')->nullable();
			$table->string('template_signature_y')->nullable();
			$table->integer('template_statement_id')->nullable();
			$table->string('code_api')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products');
	}

}

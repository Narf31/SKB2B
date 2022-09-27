<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBsoItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bso_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('bso_supplier_id')->default(0)->comment('СК - поставщик БСО');
			$table->integer('insurance_companies_id')->default(0)->comment('СК - эмитент');
			$table->integer('point_sale_id')->default(1)->comment('Точка продаж');
			$table->integer('org_id')->nullable();
			$table->integer('bso_class_id')->default(0);
			$table->integer('product_id')->default(0);
			$table->integer('type_bso_id')->default(0);
			$table->integer('state_id')->default(0);
			$table->integer('location_id')->default(0);
			$table->integer('user_id')->default(0);
			$table->integer('user_org_id')->default(1)->comment('Организация (используется, если у брокера более одного юр.лица)');
			$table->dateTime('time_create')->nullable();
			$table->dateTime('time_target')->nullable();
			$table->dateTime('last_operation_time')->nullable();
			$table->dateTime('transfer_to_agent_time')->nullable();
			$table->dateTime('transfer_to_org_time')->nullable();
			$table->dateTime('transfer_to_sk_time')->nullable();
			$table->integer('bso_serie_id')->default(0);
			$table->string('bso_number');
			$table->integer('bso_dop_serie_id')->default(0);
			$table->string('bso_title');
			$table->integer('bso_blank_serie_id')->default(0);
			$table->string('bso_blank_number');
			$table->integer('bso_blank_dop_serie_id')->default(0);
			$table->string('bso_blank_title');
			$table->string('bso_comment')->nullable();
			$table->integer('acts_reserve_or_realized_id')->default(0);
			$table->integer('acts_implemented_id')->default(0);
			$table->integer('acts_sk_id')->nullable()->default(0)->comment('-1 в корзине 0 в общем списке');
			$table->string('act_add_number')->nullable();
			$table->integer('bso_act_id')->nullable();
			$table->integer('transfer_id')->default(0);
			$table->integer('last_transfer_id');
			$table->integer('bso_manager_id');
			$table->integer('is_reserved')->default(0);
			$table->integer('bso_cart_id');
			$table->integer('agent_id');
			$table->integer('agent_organization_id')->default(0)->comment('Организация (используется, если у брокера более одного юр.лица)');
			$table->integer('contract_id');
			$table->integer('realized_act_id')->nullable()->default(0);
			$table->integer('file_id')->nullable()->default(0);
			$table->unique(['bso_title','type_bso_id'], 'UK_bso_items');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bso_items');
	}

}

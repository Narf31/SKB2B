<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type_id')->nullable()->default(0);
			$table->integer('supplier_organization_id')->nullable();
			$table->integer('status_id')->nullable()->default(0);
			$table->integer('user_id')->nullable();
			$table->integer('bso_id')->nullable();
			$table->integer('contract_id')->nullable();
			$table->integer('product_id')->nullable();
			$table->integer('agent_id')->nullable();
			$table->integer('agent_organization_id')->nullable();
			$table->integer('agent_parent_id')->nullable();
			$table->integer('agent_curator_id')->nullable();
			$table->dateTime('begin_date')->nullable();
			$table->integer('position_type_id')->nullable()->default(0);
			$table->integer('city_id')->nullable();
			$table->string('address')->nullable();
			$table->decimal('latitude', 9, 6)->nullable();
			$table->decimal('longitude', 9, 6)->nullable();
			$table->text('comments')->nullable();
			$table->integer('insurer_id')->nullable();
			$table->integer('point_sale_id')->nullable();
			$table->integer('insurer_type_id')->nullable()->default(0);
			$table->string('phone')->nullable();
			$table->string('email')->nullable();
			$table->integer('work_user_id')->nullable()->default(0);
			$table->integer('work_status_id')->nullable()->default(0);
			$table->string('insurer_title')->nullable();
			$table->string('comment_pso')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('email')->unique();
			$table->string('password');
			$table->integer('subject_type_id');
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
			$table->integer('role_id')->unsigned()->nullable();
			$table->string('work_phone')->nullable();
			$table->string('mobile_phone')->nullable();
			$table->integer('organization_id')->unsigned()->nullable();
			$table->integer('image_id')->unsigned()->nullable();
			$table->integer('small_image_id')->unsigned()->nullable();
			$table->integer('status_user_id')->nullable()->default(0);
			$table->integer('status_security_service')->nullable()->default(0);
			$table->integer('is_parent')->nullable()->default(0);
			$table->integer('parent_id')->unsigned()->nullable();
			$table->integer('curator_id')->nullable();
			$table->integer('financial_group_id')->nullable()->default(0);
			$table->integer('subject_id');
			$table->integer('department_id');
			$table->integer('filial_id');
			$table->integer('ban_level')->nullable()->default(-1);
			$table->string('ban_reason')->nullable();
			$table->integer('point_sale_id')->nullable();
			$table->string('agent_contract_title')->nullable();
			$table->date('agent_contract_begin_date')->nullable();
			$table->date('agent_contract_end_date')->nullable();
			$table->text('settings', 65535);
			$table->string('front_user_id')->nullable();
			$table->string('front_user_title')->nullable();
			$table->string('path_parent')->nullable();
			$table->text('products_sale')->nullable();
			$table->integer('text_size')->nullable()->default(17);
			$table->integer('sales_condition')->nullable()->default(0);
			$table->integer('is_work')->nullable()->default(0);
			$table->string('latitude')->nullable();
			$table->string('longitude')->nullable();
			$table->string('export_user_id')->nullable();
			$table->string('export_parent_id')->nullable();
			$table->integer('is_notification')->nullable()->default(1);
			$table->string('last_session_id')->nullable();
			$table->string('apiToken')->nullable();
			$table->dateTime('apiTokenTime')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}

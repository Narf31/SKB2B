<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMatchingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('matching', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('type_id')->nullable()->default(0);
			$table->integer('category_id')->nullable()->default(0);
			$table->string('category_title')->nullable()->default('');
			$table->integer('status_id')->nullable()->default(0);
			$table->integer('check_user_id')->nullable();
			$table->dateTime('check_date')->nullable();
			$table->integer('initiator_user_id')->nullable();
			$table->integer('initiator_organization_id')->nullable();
			$table->integer('initiator_parent_id')->nullable();
			$table->integer('initiator_curator_id')->nullable();
			$table->text('comments')->nullable();
			$table->integer('contract_id')->nullable();
			$table->integer('product_id')->nullable();
			$table->string('insurer_title')->nullable();
			$table->integer('supplementary_id')->nullable();
			$table->text('agent_comments')->nullable();
			$table->integer('is_urgently')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('matching');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralSubjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_subjects', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type_id')->nullable()->default(0);
			$table->string('title')->nullable()->default('');
			$table->string('lat')->nullable()->default('');
			$table->string('phone')->nullable();
			$table->string('email')->nullable();
			$table->string('password')->nullable();
			$table->integer('is_resident')->nullable()->default(1);
			$table->integer('citizenship_id')->nullable()->default(51);
			$table->integer('user_id')->nullable();
			$table->integer('user_organization_id')->nullable();
			$table->integer('user_parent_id')->nullable();
			$table->integer('user_curator_id')->nullable();
			$table->text('hash', 65535)->nullable();
			$table->text('json_data')->nullable();
			$table->text('comments')->nullable();
			$table->integer('person_category_id')->nullable()->default(1);
			$table->integer('status_work_id')->nullable()->default(0);
			$table->string('inn')->nullable();
			$table->integer('risk_level_id')->nullable()->default(0);
			$table->string('risk_base')->nullable();
			$table->dateTime('risk_date')->nullable();
			$table->string('risk_history')->nullable();
			$table->integer('risk_user_id')->nullable();
			$table->text('risk_comments', 65535)->nullable();
			$table->integer('export_id')->nullable()->default(0);
			$table->integer('export_org_id')->nullable()->default(0);
			$table->integer('export_is_connection')->nullable()->default(0);
			$table->string('label')->nullable()->default('');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_subjects');
	}

}

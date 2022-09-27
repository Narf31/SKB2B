<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralPodftFlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_podft_fl', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('general_subject_id')->nullable();
			$table->string('financial_business_objectives')->nullable()->default('Извличение прибыли');
			$table->string('information_business_reputation')->nullable()->default('Продолжительные');
			$table->string('alleged_nature_relationship')->nullable()->default('Долгосрочный');
			$table->string('origin_ds_other_property')->nullable()->default('Сбор ср-ва');
			$table->string('purpose_establishing_relationship')->nullable()->default('');
			$table->string('financial_position')->nullable()->default('Положительное');
			$table->integer('is_executor_state_municipal')->nullable()->default(0);
			$table->integer('is_recipient_grants')->nullable()->default(0);
			$table->integer('is_participant_targeted_programs_national')->nullable()->default(0);
			$table->integer('is_recipient_state_support')->nullable()->default(0);
			$table->integer('main_type_employment_id')->nullable()->default(0);
			$table->string('main_type_employment_text')->nullable()->default('');
			$table->integer('general_organization_id')->nullable();
			$table->string('job_department_subdivision')->nullable()->default('');
			$table->string('job_phone')->nullable()->default('');
			$table->string('job_position')->nullable()->default('');
			$table->integer('job_credentials_id')->nullable();
			$table->integer('job_type_activity_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_podft_fl');
	}

}

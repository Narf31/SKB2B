<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralPodftUlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_podft_ul', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('general_subject_id')->nullable();
			$table->string('purpose_establishing_relationship')->nullable()->default('Заключение договора страхования');
			$table->string('financial_business_objectives')->nullable()->default('Коммерческая деятельность с целью получения прибыли');
			$table->string('financial_position')->nullable()->default('Положительное');
			$table->string('information_business_reputation')->nullable()->default('Положительная');
			$table->integer('in_whose_interests_id')->nullable()->default(1);
			$table->integer('is_recipient_grants')->nullable()->default(0);
			$table->integer('is_budgetary_institution')->nullable()->default(0);
			$table->integer('is_founder')->nullable()->default(0);
			$table->integer('is_beneficiary')->nullable()->default(0);
			$table->integer('is_documents_submitted_paper')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_podft_ul');
	}

}

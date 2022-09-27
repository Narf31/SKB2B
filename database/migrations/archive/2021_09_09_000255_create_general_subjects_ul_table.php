<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeneralSubjectsUlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_subjects_ul', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('general_subject_id')->nullable();
			$table->integer('general_manager_id')->nullable();
			$table->integer('of_id')->nullable()->default(1);
			$table->string('full_title')->nullable();
			$table->string('full_title_en')->nullable();
			$table->integer('ownership_id')->nullable()->default(4);
			$table->string('inn')->nullable();
			$table->string('kpp')->nullable();
			$table->string('ogrn')->nullable();
			$table->date('date_orgn')->nullable();
			$table->string('issued')->nullable();
			$table->string('place_registration')->nullable();
			$table->integer('bank_id')->nullable()->default(4);
			$table->string('bik')->nullable();
			$table->string('rs')->nullable();
			$table->string('ks')->nullable();
			$table->decimal('share_capital', 11)->nullable()->default(10000.00);
			$table->string('presence_permanent_management_body')->nullable();
			$table->string('license_information')->nullable();
			$table->string('management_structure')->nullable();
			$table->string('undertaken_identify_beneficial')->nullable();
			$table->string('okpo')->nullable();
			$table->string('oktmo')->nullable();
			$table->string('okfs')->nullable();
			$table->string('okato')->nullable();
			$table->string('okogy')->nullable();
			$table->string('okopf')->nullable();
			$table->integer('general_accountant_id')->nullable();
			$table->string('okved_code')->nullable();
			$table->string('okved_title')->nullable();
			$table->text('okved_complementary')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_subjects_ul');
	}

}

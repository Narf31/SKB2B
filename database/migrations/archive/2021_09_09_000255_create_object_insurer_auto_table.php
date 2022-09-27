<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateObjectInsurerAutoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('object_insurer_auto', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('object_insurer_id')->nullable()->default(0);
			$table->integer('ts_category')->nullable()->default(2);
			$table->integer('mark_id')->nullable();
			$table->integer('model_id')->nullable();
			$table->integer('car_year')->nullable();
			$table->integer('purpose_id')->nullable();
			$table->string('vin')->nullable();
			$table->string('body_number')->nullable();
			$table->string('body_chassis')->nullable();
			$table->integer('type_reg_number')->nullable();
			$table->string('reg_number')->nullable();
			$table->decimal('power', 11)->nullable();
			$table->decimal('powerkw', 11)->nullable();
			$table->decimal('weight', 11)->nullable();
			$table->decimal('capacity', 11)->nullable();
			$table->integer('passengers_count')->nullable();
			$table->integer('is_trailer')->nullable()->default(0);
			$table->integer('doc_type')->nullable()->default(0);
			$table->string('docserie')->nullable();
			$table->string('docnumber')->nullable();
			$table->date('docdate')->nullable();
			$table->string('dk_number')->nullable();
			$table->date('dk_date_from')->nullable();
			$table->date('dk_date_to')->nullable();
			$table->integer('country_id')->nullable()->default(51);
			$table->integer('count_key')->nullable()->default(2);
			$table->integer('anti_theft_system_id')->nullable();
			$table->integer('color_id')->nullable();
			$table->integer('engine_type_id')->nullable()->default(1);
			$table->decimal('volume', 11)->nullable();
			$table->decimal('mileage', 11)->nullable();
			$table->decimal('car_price', 11)->nullable();
			$table->integer('number_owners')->nullable()->default(1);
			$table->integer('source_acquisition_id')->nullable()->default(1);
			$table->integer('is_credit')->nullable()->default(0);
			$table->integer('is_autostart')->nullable()->default(0);
			$table->integer('is_right_drive')->nullable()->default(0);
			$table->integer('is_duplicate')->nullable()->default(0);
			$table->integer('transmission_type')->nullable()->default(1);
			$table->string('mark_code')->nullable();
			$table->string('model_code')->nullable();
			$table->string('model_classification_code')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('object_insurer_auto');
	}

}

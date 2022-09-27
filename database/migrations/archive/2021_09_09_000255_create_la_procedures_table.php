<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLaProceduresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('la_procedures', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->nullable();
			$table->string('title')->nullable();
			$table->string('organization_title')->nullable();
			$table->string('inn')->nullable();
			$table->string('ogrn')->nullable();
			$table->string('address')->nullable();
			$table->decimal('latitude', 9, 6)->nullable();
			$table->decimal('longitude', 9, 6)->nullable();
			$table->text('content_html')->nullable();
			$table->integer('general_subject_id')->nullable();
			$table->integer('bankruptcy_procedures_id')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('la_procedures');
	}

}

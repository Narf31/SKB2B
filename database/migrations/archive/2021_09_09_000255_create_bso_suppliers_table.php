<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBsoSuppliersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bso_suppliers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('insurance_companies_id')->nullable()->default(0);
			$table->string('title')->nullable();
			$table->string('signer')->nullable();
			$table->integer('source_org_id')->nullable()->default(0);
			$table->integer('purpose_org_id')->nullable()->default(0);
			$table->integer('city_id')->nullable()->default(0);
			$table->integer('is_actual')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bso_suppliers');
	}

}

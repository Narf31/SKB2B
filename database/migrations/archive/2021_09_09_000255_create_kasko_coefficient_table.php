<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKaskoCoefficientTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kasko_coefficient', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id');
			$table->integer('program_id');
			$table->integer('organization_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('category')->nullable();
			$table->string('group')->nullable();
			$table->string('type')->nullable();
			$table->string('tarrif_name')->nullable();
			$table->string('field')->nullable();
			$table->decimal('tarife', 11)->nullable()->default(0.00);
			$table->string('value')->nullable();
			$table->string('value_to')->nullable();
			$table->string('value_from')->nullable();
			$table->integer('is_adjacent')->nullable()->default(0);
			$table->text('json')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kasko_coefficient');
	}

}

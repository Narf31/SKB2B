<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTemplateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('template_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('parent_id');
			$table->char('code');
			$table->char('title');
			$table->integer('has_choise')->default(1);
			$table->integer('has_supplier')->default(0);
			$table->integer('is_actual')->default(1);
			$table->integer('output_extension')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('template_categories');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsSpecialSettingsFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_special_settings_files', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('special_settings_id')->nullable();
			$table->integer('file_id')->nullable();
			$table->string('type_name')->nullable();
			$table->string('template_print')->nullable();
			$table->integer('template_print_page')->nullable();
			$table->string('template_print_x')->nullable();
			$table->string('template_print_y')->nullable();
			$table->string('template_signature')->nullable();
			$table->string('template_signature_x')->nullable();
			$table->string('template_signature_y')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products_special_settings_files');
	}

}

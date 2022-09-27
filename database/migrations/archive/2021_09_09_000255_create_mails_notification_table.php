<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMailsNotificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mails_notification', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->dateTime('send_at')->nullable();
			$table->string('user_email')->nullable();
			$table->string('template')->nullable();
			$table->string('title')->nullable();
			$table->text('body', 65535)->nullable();
			$table->string('url')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mails_notification');
	}

}

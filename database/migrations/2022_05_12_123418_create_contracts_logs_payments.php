<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsLogsPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts_logs_payments', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('contract_id');
            $table->integer('user_id')->nullable()->default(null);

            $table->decimal('payment_total', 11,2)->nullable()->default(null);
            $table->text('text')->nullable()->default(null);
            $table->longText('json')->nullable()->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts_logs_payments');
    }
}

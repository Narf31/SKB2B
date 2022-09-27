<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKaskoDopwhere extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kasko_dopwhere', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('program_id');
            $table->integer('organization_id')->nullable()->default(null);
            $table->integer('user_id')->nullable()->default(null);


            $table->string('category')->nullable()->default(null);
            $table->string('group')->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
            $table->string('tarrif_name')->nullable()->default(null);
            $table->string('field')->nullable()->default(null);
            $table->decimal('tarife', 11,2)->nullable()->default(0);

            $table->string('value')->nullable()->default(null);
            $table->string('value_to')->nullable()->default(null);
            $table->string('value_from')->nullable()->default(null);



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kasko_dopwhere');
    }
}

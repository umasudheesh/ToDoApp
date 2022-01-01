<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToDoTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('to_do_task', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api_token',100)->nullable();
            $table->string('api_token',100)->nullable();
            $table->string('api_token',100)->nullable();
            $table->string('api_token',100)->nullable();
            $table->string('api_token',100)->nullable();

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
        Schema::dropIfExists('to_do_task');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('task_id');
            $table->datetime('reminder_datetime')->nullable();
            $table->string('type', 100)->nullable();
            $table->boolean('status')->default(0);
            $table->foreign('task_id')->references('id')->on('to_do_task')->onDelete('cascade');
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
        Schema::dropIfExists('task_reminders');
    }
}

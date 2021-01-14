<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_history', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('request_id', false, true);
            $table->foreign('request_id')->references('id')->on('requests');

            $table->bigInteger('user_id', false, true);
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('status_id', false, true);
            $table->foreign('status_id')->references('id')->on('request_statuses');

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
        Schema::dropIfExists('request_history');
    }
}

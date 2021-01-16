<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_files', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('request_id', false, true);
            $table->foreign('request_id')->references('id')->on('requests');

            $table->string('original_name', 255);
            $table->string('name', 255);
            $table->string('type', 5);

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
        Schema::dropIfExists('request_files');
    }
}

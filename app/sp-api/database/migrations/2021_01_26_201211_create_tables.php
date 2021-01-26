<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 256);
            $table->string('email', 256);
        });


        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('user');
            $table->foreign('owner_id')->references('id')->on('user');

            $table->dateTime('registration_start');
            $table->dateTime('registration_end');

            $table->dateTime('event_start');
            $table->dateTime('event_end')->nullable();

            $table->integer('max_participants');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}

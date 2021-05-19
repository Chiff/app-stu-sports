<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Notifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('html_content');
            $table->bigInteger('entity_id')->unsigned();
            $table->bigInteger('entity_type')->unsigned();
            $table->foreign('entity_type')->references('id')->on('ciselnik');

        });

        DB::table('ciselnik')->insert(array('label' => 'Team','type' => 'ENTITY_TYPE'));
        DB::table('ciselnik')->insert(array('label' => 'User','type' => 'ENTITY_TYPE'));
        DB::table('ciselnik')->insert(array('label' => 'Event','type' => 'ENTITY_TYPE'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}

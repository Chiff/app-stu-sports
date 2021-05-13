<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ciselniky extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciselnik', function (Blueprint $table) {
            $table->id();

            $table->string('label', 2048);
            $table->string('group', 512)->nullable();
            $table->string('type', 256);
        });

        DB::table('ciselnik')->insert(array('label' => 'Futbal','type' => 'EVENT_TYPE'));
        DB::table('ciselnik')->insert(array('label' => 'Hokej','type' => 'EVENT_TYPE'));
        DB::table('ciselnik')->insert(array('label' => 'Volejbal','type' => 'EVENT_TYPE'));

        Schema::table('events', function (Blueprint $table) {
            $table->string('description', 4096)->nullable(true);

            $table->bigInteger('type')->unsigned()->nullable();
            $table->foreign('type')->references('id')->on('ciselnik');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciselnik');
        Schema::dropColumns('events', 'description');
        Schema::dropColumns('events', 'type');
    }
}

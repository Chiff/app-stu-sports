<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewOrmRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->bigInteger('id')->change()->unsigned()->autoIncrement();
//            $table->id()->change()->type('integer')->unsigned()->autoIncrement();
            $table->bigInteger('event_id')->unsigned();
            $table->string('ext_id');
            $table->foreign('event_id')->references('id')->on('user')->onDelete('cascade');
            $table->integer('min_teams');
            $table->integer('max_teams');
            $table->integer('min_team_members');
            $table->integer('max_team_members');

            $table->dropColumn('max_participants');
        });

        Schema::create('user_event', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('event_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        Schema::create('team', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->timestamps();

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');

            $table->string('team_name')->unique();

        });

        Schema::create('user_team', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('team_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('team')->onDelete('cascade');
        });

        Schema::create('event_team', function (Blueprint $table) {
            $table->bigInteger('event_id')->unsigned();
            $table->bigInteger('team_id')->unsigned();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('team')->onDelete('cascade');
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team');
        Schema::dropIfExists('user_event');
        Schema::dropIfExists('user_team');
        Schema::dropIfExists('event_team');

    }
}

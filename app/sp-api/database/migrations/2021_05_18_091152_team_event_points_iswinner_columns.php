<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TeamEventPointsIswinnerColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_team', function(Blueprint $table) {
            $table->integer('points')->nullable();
            $table->boolean('is_winner')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        throw new Exception("rollback not supported, clear db pls (php artisan migrate:fresh)");
    }
}

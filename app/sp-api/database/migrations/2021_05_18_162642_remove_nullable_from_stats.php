<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNullableFromStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_team', function (Blueprint $table) {
            $table->integer('points')->nullable(false)->change();
            $table->integer('points')->default(0)->change();
        });
        Schema::table('team', function(Blueprint $table) {
            $table->integer('points')->nullable(false)->change();
            $table->integer('wins')->nullable(false)->change();
            $table->integer('events_total')->nullable(false)->change();

            $table->integer('points')->default(0)->change();
            $table->integer('wins')->default(0)->change();
            $table->integer('events_total')->default(0)->change();
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

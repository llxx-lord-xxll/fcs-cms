<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteSchedulesMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_schedules_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schedule_id');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->integer('speakers')->comment('Points to Team ID');
            $table->integer('moderator')->comment('Points to People ID');
            $table->string('time_period_start');
            $table->string('time_period_end');
            $table->string('location');
            $table->string('host')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_schedules_meta');
    }
}

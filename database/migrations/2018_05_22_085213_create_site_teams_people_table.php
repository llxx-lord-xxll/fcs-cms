<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteTeamsPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_teams_people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('photo');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('city');
            $table->string('country');
            $table->string('profession');
            $table->date('dob');
            $table->string('fb');
            $table->string('li');
            $table->string('tt');
            $table->string('web');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_teams_people');
    }
}

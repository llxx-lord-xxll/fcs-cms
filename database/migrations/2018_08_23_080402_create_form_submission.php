<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormSubmission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_forms_submissioon', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('form_id');
            $table->string('ip');
            $table->string('ua');
            $table->string('stripe_charge')->nullable();
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
        Schema::dropIfExists('site_forms_submissioon');
    }
}

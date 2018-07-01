<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SiteFormSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_forms_subscription', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('form_id');
            $table->string('list_field_name');
            $table->string('form_field_name');
            $table->string('subscription_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_forms_subscription');
    }
}

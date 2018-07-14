<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('table_name')->nullable();
            $table->string('submit_button');
            $table->string('agreement_html')->nullable();
            $table->string('subscribers', 1);
            $table->string('subscribers_confname')->nullable();
            $table->string('newsletter', 1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_forms');
    }
}

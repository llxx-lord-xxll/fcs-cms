<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteTemplatesMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_templates_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('templates_id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->integer('widgets_id');
            $table->string('title');
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
        Schema::dropIfExists('site_templates_meta');
    }
}

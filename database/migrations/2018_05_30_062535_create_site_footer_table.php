<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteFooterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_footer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('meta_key')->unique();
            $table->string('meta_value')->nullable();
            $table->string('meta_type');
            $table->integer('parent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_footer');
    }
}

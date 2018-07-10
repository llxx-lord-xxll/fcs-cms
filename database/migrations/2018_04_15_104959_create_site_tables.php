<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('site_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50);
        });

        Schema::connection($connection)->create(config('admin.database.site_menu_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->bigIncrements('menu_id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50);
            $table->string('uri', 50)->nullable();
        });


        Schema::connection($connection)->create(config('admin.database.site_widgets_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        Schema::connection($connection)->dropIfExists('site_menus');
        Schema::connection($connection)->dropIfExists(config('admin.database.site_menu_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.site_widgets_table'));
    }
}

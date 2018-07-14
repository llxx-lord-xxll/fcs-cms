<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteFormEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_form_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('form_id');
            $table->string('field_name');
            $table->text('field_title')->nullable();
            $table->string('field_rules')->nullable();
            $table->text('field_instructions')->nullable();
            $table->string('field_type');
            $table->string('field_ivals')->nullable();
            $table->string('field_placeholder')->nullable();
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
        Schema::dropIfExists('site_form_entries');
    }
}

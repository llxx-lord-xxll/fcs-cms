<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsOnForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_forms', function ($table) {
            $table->string('payment', 1)->default(0);
            $table->double('payment_charge')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_forms', function (Blueprint $table) {
            $table->dropColumn('payment');
            $table->dropColumn('payment_charge');
        });
    }
}

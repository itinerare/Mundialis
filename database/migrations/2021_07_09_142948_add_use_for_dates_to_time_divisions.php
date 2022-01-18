<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUseForDatesToTimeDivisions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('time_divisions', function (Blueprint $table) {
            //
            $table->boolean('use_for_dates')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('time_divisions', function (Blueprint $table) {
            //
            $table->dropColumn('use_for_dates');
        });
    }
}

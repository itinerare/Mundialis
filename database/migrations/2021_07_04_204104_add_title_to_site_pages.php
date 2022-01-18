<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitleToSitePages extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('site_pages', function (Blueprint $table) {
            //
            $table->string('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('site_pages', function (Blueprint $table) {
            //
            $table->dropColumn('title');
        });
    }
}

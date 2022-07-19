<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePagesDataToNullable extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('pages', function (Blueprint $table) {
            //
            $table->longText('data')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::table('pages', function (Blueprint $table) {
            //
            $table->longText('data')->change();
        });
    }
}

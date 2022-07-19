<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSettingsTable extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('key')->index();
            $table->string('value');
            $table->string('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('site_settings');
    }
}

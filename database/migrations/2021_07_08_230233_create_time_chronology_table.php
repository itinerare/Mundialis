<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeChronologyTable extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        // Add chronologies table
        Schema::create('time_chronology', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('parent_id')->nullable()->default(null);

            // Basic characteristics
            $table->string('name');
            $table->string('abbreviation')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);

            $table->integer('sort')->default(0);
        });

        // Add divisions table
        Schema::create('time_divisions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            // Basic characteristics
            $table->string('name');
            $table->string('abbreviation')->nullable()->default(null);
            // This will be used to store how many of the unit are in the next
            // division up (e.g. 24 hours (to a day))
            $table->integer('unit')->nullable()->default(null);

            $table->integer('sort')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('time_chronology');
        Schema::dropIfExists('time_divisions');
    }
}

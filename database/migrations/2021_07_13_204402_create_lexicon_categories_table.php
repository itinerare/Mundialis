<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLexiconCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lexicon_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('parent_id')->nullable()->default(null);

            // Basic characteristics
            $table->string('name');
            $table->text('description')->nullable()->default(null);

            $table->text('data')->nullable()->default(null);

            $table->integer('sort')->default(0);
        });

        Schema::create('lexicon_settings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('name');
            $table->string('abbreviation')->nullable()->default(null);
            $table->integer('sort')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('lexicon_categories');
        Schema::dropIfExists('lexicon_settings');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('subject_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            // Subject and parent (if set) for searching on
            $table->string('subject')->index();
            $table->integer('parent_id')->nullable()->default(null);

            // Basic info
            $table->string('name');
            $table->text('description')->nullable()->default(null);

            // Template builder data
            $table->text('data')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('subject_categories');
    }
}

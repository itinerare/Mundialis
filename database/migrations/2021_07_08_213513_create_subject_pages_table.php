<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectPagesTable extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('pages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('category_id')->index();

            $table->string('title');
            $table->string('summary', 255)->nullable()->default(null);
            // Page data will contain the entire content of the page,
            // so it needs to be able to hold a lot of information
            $table->longText('data');

            $table->boolean('is_visible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('pages');
    }
}

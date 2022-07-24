<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageRelationshipsTable extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('page_relationships', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('page_one_id')->unsigned()->index();
            $table->integer('page_two_id')->unsigned()->index();

            $table->string('type_one');
            $table->string('type_one_info')->nullable()->default(null);
            $table->text('details_one')->nullable()->default(null);

            $table->string('type_two');
            $table->string('type_two_info')->nullable()->default(null);
            $table->text('details_two')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('page_relationships');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageTagTable extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        // Tags will be able to be created on the fly,
        // so this is a relatively straightforward affair
        Schema::create('page_tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('page_id')->unsigned()->index();

            // Type is to distinguish between regular (user-created)
            // and utility/system tags (such as WIP, stub, etc.)
            $table->enum('type', ['utility', 'page_tag']);
            // Whereas 'tag' is just the tag itself
            $table->string('tag')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('page_tags');
    }
}

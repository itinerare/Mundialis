<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageLinksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('page_links', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            // This table is largely a behind-the-scenes recordkeeping
            // utility, so it likely does not need indices...

            // The ID of the page doing the linking
            $table->integer('page_id');
            // The ID of the page being linked to, if present
            $table->integer('link_id')->nullable()->default(null);
            // The title of the page, if wanted
            $table->string('title')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('page_links');
    }
}

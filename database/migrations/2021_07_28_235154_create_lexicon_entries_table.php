<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLexiconEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lexicon_entries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('category_id')->unsigned()->nullable()->default(null)->index();
            $table->string('class');

            $table->string('word');
            $table->string('meaning', 255);
            $table->string('pronunciation', 255)->nullable()->default(null);
            $table->text('definition')->nullable()->default(null);

            $table->text('data')->nullable()->default(null);
            $table->boolean('is_visible')->default(1);

            $table->timestamps();
        });

        Schema::create('lexicon_etymologies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            // The entry the link is for
            $table->integer('entry_id')->unsigned()->index();
            // The entry the link is to, or external string
            $table->integer('parent_id')->unsigned()->nullable()->default(null);
            $table->string('parent')->nullable()->default(null);
        });

        Schema::table('page_links', function (Blueprint $table) {
            // Update to allow for links from entries
            $table->renameColumn('page_id', 'parent_id');
            $table->string('parent_type')->default('page');
            $table->string('linked_type')->default('page');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lexicon_entries');
        Schema::dropIfExists('lexicon_etymologies');

        Schema::table('page_links', function (Blueprint $table) {
            $table->renameColumn('parent_id', 'page_id');
            $table->dropColumn('parent_type');
            $table->dropColumn('linked_type');
        });
    }
}

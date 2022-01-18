<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParsedDescriptionToLexiconEntriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('lexicon_entries', function (Blueprint $table) {
            //
            $table->text('parsed_definition')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('lexicon_entries', function (Blueprint $table) {
            //
            $table->dropColumn('parsed_definition');
        });
    }
}

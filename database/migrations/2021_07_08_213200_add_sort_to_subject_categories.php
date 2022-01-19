<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortToSubjectCategories extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('subject_categories', function (Blueprint $table) {
            //
            $table->integer('sort')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('subject_categories', function (Blueprint $table) {
            //
            $table->dropColumn('sort');
        });
    }
}

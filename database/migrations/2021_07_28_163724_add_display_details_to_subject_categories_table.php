<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisplayDetailsToSubjectCategoriesTable extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('subject_categories', function (Blueprint $table) {
            //
            $table->string('summary', 255)->nullable()->default(null);
            $table->boolean('has_image')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::table('subject_categories', function (Blueprint $table) {
            //
            $table->dropColumn('summary');
            $table->dropColumn('has_image');
        });
    }
}

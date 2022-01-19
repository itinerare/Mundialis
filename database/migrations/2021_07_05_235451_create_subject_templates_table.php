<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('subject_templates', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('subject')->index();
            $table->text('data')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('subject_templates');
    }
}

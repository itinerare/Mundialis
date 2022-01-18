<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageProtectionTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('page_protections', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('page_id')->unsigned()->index();

            // This table, like versions, contains both status and log
            // of past protection status
            $table->integer('user_id')->unsigned();
            $table->boolean('is_protected')->default(0);

            $table->string('reason')->nullable()->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('page_protections');
    }
}

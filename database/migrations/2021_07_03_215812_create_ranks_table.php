<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('name');
            $table->string('description')->nullable()->default(null);

            $table->integer('sort')->default(0);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('rank_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ranks');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rank_id');
        });
    }
}

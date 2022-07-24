<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserUpdateLog extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('user_update_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('staff_id')->unsigned()->nullable()->default(null);
            $table->integer('user_id')->unsigned()->index();

            $table->string('type');
            $table->string('data', 512);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('user_update_log');
    }
}

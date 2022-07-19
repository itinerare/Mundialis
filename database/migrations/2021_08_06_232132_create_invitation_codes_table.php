<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationCodesTable extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('invitation_codes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('code')->index();
            $table->integer('user_id')->unsigned();
            $table->integer('recipient_id')->unsigned()->nullable()->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('invitation_codes');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('user_id')->unsigned()->index();
            $table->integer('notification_type_id')->unsigned();

            $table->boolean('is_unread')->default(1);
            $table->string('data', 1024)->nullable()->default(null);

            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            //
            $table->integer('notifications_unread')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');

        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('notifications_unread');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageImageTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('page_images', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            // Basic image information
            $table->string('hash');
            $table->string('extension', 5);
            $table->text('description')->nullable()->default(null);

            // Cropper information
            $table->boolean('use_cropper')->default(0);
            $table->integer('x0')->nullable()->default(null);
            $table->integer('x1')->nullable()->default(null);
            $table->integer('y0')->nullable()->default(null);
            $table->integer('y1')->nullable()->default(null);

            $table->boolean('is_visible')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('page_image_creators', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('page_image_id')->index();

            // If the creator is on site
            $table->integer('user_id')->nullable()->default(null);
            // If the creator is not on site
            // This will be formatted using a helper function
            $table->string('url')->nullable()->default(null);
        });

        Schema::create('page_page_image', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('page_id')->unsigned();
            $table->integer('page_image_id')->unsigned();

            $table->boolean('is_valid')->default(1);
        });

        Schema::table('pages', function (Blueprint $table) {
            // Add ID for primary image and soft deletes column
            $table->integer('image_id')->nullable()->default(null);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('page_images');
        Schema::dropIfExists('page_image_creators');
        Schema::dropIfExists('page_page_image');

        Schema::table('pages', function (Blueprint $table) {
            //
            $table->dropColumn('image_id');
            $table->dropSoftDeletes();
        });
    }
}

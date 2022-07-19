<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageRevisionsTable extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('page_versions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('page_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();

            // Version information
            $table->string('type');
            $table->string('reason')->nullable()->default(null);
            $table->integer('is_minor')->default(0);
            $table->longText('data')->nullable()->default(null);

            $table->timestamps();
        });

        Schema::create('page_image_versions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('page_image_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();

            // Image information
            $table->string('hash')->nullable()->default(null);
            $table->string('extension', 5)->nullable()->default(null);

            // Cropper information
            $table->boolean('use_cropper')->default(0);
            $table->integer('x0')->nullable()->default(null);
            $table->integer('x1')->nullable()->default(null);
            $table->integer('y0')->nullable()->default(null);
            $table->integer('y1')->nullable()->default(null);

            // Version information
            $table->string('type');
            $table->string('reason')->nullable()->default(null);
            $table->integer('is_minor')->default(0);
            $table->text('data')->nullable()->default(null);

            $table->timestamps();
        });

        Schema::table('page_page_image', function (Blueprint $table) {
            // Index page image link IDs
            $table->index('page_id');
            $table->index('page_image_id');
        });

        // Remove columns for information moved to respective version tables
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('data');
        });

        Schema::table('page_images', function (Blueprint $table) {
            $table->dropColumn('hash');
            $table->dropColumn('extension');

            $table->dropColumn('use_cropper');
            $table->dropColumn('x0');
            $table->dropColumn('x1');
            $table->dropColumn('y0');
            $table->dropColumn('y1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('page_versions');
        Schema::dropIfExists('page_image_versions');

        Schema::table('page_page_image', function (Blueprint $table) {
            //
            $table->dropIndex(['page_id']);
            $table->dropIndex(['page_image_id']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->longText('data')->nullable()->default(null);
        });

        Schema::table('page_images', function (Blueprint $table) {
            // Image information
            $table->string('hash');
            $table->string('extension', 5);

            // Cropper information
            $table->boolean('use_cropper')->default(0);
            $table->integer('x0')->nullable()->default(null);
            $table->integer('x1')->nullable()->default(null);
            $table->integer('y0')->nullable()->default(null);
            $table->integer('y1')->nullable()->default(null);
        });
    }
}

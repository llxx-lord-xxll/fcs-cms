<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_blog_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('author_ID');
            $table->bigInteger('category_ID')->nullable();
            $table->longText('post_content');
            $table->text('post_title');
            $table->string('post_thumb')->nullable();
            $table->string('post_slug', 200);
            $table->string('post_type', 20);
            $table->integer('post_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_blog_posts');
    }
}

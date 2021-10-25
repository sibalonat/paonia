<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });
        // Schema::create('article_tag', function (Blueprint $table) {
        //     $table->unsignedBigInteger('article_id');
        //     $table->unsignedBigInteger('tag_id');
            // $table->integer('article_id')->unsigned()->nullable();
            // $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            // $table->integer('tag_id')->unsigned()->nullable();
            // $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            // $table->unsignedBigInteger('article_id');
            // $table->bigInteger('article_id')->unsigned()->index();
            // $table->bigInteger('tag_id')->unsigned()->index();
            // $table->unique(['article_id', 'tag_id']);
            // $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            // $table->unsignedBigInteger('tag_id');
            // $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('article_tag');
        Schema::dropIfExists('tags');
    }
}

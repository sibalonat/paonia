<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TagThread extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_thread', function (Blueprint $table) {
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('tag_id');
            $table->unique(['thread_id', 'tag_id']);
            $table->foreign('thread_id')->references('id')->on('threads')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
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
        Schema::dropIfExists('tag_thread');
    }
}

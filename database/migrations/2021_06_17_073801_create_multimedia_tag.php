<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultimediaTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multimedia_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('multimedia_id');
            $table->unsignedBigInteger('tag_id');
            $table->unique(['multimedia_id', 'tag_id']);
            $table->foreign('multimedia_id')->references('id')->on('multimedia')->onDelete('cascade');
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
        Schema::dropIfExists('multimedia_tag');
    }
}

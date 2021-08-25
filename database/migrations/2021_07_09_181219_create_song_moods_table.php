<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongMoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_moods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('songId');
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('themeId');
            $table->foreign('songId')->references('id')->on('music')->onDelete('cascade');
            $table->foreign('userId')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('themeId')->references('id')->on('themes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('song_moods');
    }
}

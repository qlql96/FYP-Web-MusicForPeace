<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistMusicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_music', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('songId');
            $table->unsignedBigInteger('playlistId');
            $table->foreign('songId')->references('id')->on('music')
                ->onDelete('cascade');
            $table->foreign('playlistId')->references('id')->on('playlists')
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
        Schema::dropIfExists('playlist_music');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music', function (Blueprint $table) {
            $table->id();
            $table->string('musicTitle');
            $table->string('songSrc');
            $table->string('songPicSrc');
            $table->string('videoSrc')->nullable();
            $table->string('token');
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('albumId')->nullable();
            $table->unsignedBigInteger('genreId')->nullable();
            $table->unsignedBigInteger('themeId')->nullable();
            $table->string('musicYear');
            $table->string('musicLyrics',3000);
            $table->string('musicLyricsWriter');
            $table->string('musicInstrumentalMaker');
            $table->string('musicProducer');
            $table->string('musicPerformer');
            $table->integer('musicNoOfLikes');
            $table->integer('musicNoOfPlays');
            $table->integer('musicNoOfShares');
            $table->foreign('albumId')->references('id')->on('albums')->onDelete('set null');
            $table->foreign('genreId')->references('id')->on('genres')->onDelete('set null');
            $table->foreign('themeId')->references('id')->on('themes')->onDelete('set null');
            $table->foreign('userId')->references('id')->on('users')
                ->onDelete('cascade');

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
        Schema::dropIfExists('music');
    }
}

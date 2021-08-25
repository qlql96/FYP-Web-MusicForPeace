<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLyricSentimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lyric_sentiments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('songId');
            $table->unsignedBigInteger('anger');
            $table->unsignedBigInteger('disgust');
            $table->unsignedBigInteger('fear');
            $table->unsignedBigInteger('joy');
            $table->unsignedBigInteger('sadness');
            $table->unsignedBigInteger('trust');
            $table->unsignedBigInteger('anticipation');
            $table->unsignedBigInteger('surprise');
            $table->float('positive');
            $table->float('negative');
            $table->foreign('songId')->references('id')->on('music')
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
        Schema::dropIfExists('lyric_sentiments');
    }
}

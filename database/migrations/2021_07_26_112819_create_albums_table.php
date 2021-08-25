<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('albumTitle');
            $table->unsignedBigInteger('userId');
            $table->string('albumPicSrc');
            $table->unsignedBigInteger('albumYear');
            $table->Integer('musicNoOfLikes');
            $table->Integer('musicNoOfShares');
            $table->foreign('userId')->references('id')->on('users')
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
        Schema::dropIfExists('albums');
    }
}

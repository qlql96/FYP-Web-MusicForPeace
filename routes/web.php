<?php

use App\Http\Controllers\UserController;
use App\Http\Livewire\Music\EditMusic;
use App\Http\Livewire\Music\UploadMusic;
use App\Http\Livewire\Music\AllMusic;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function(){

    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::get('/music/{user}/upload', UploadMusic::class)->name('music.upload');
    Route::get('/music/{user}/{music}/edit', EditMusic::class)->name('music.edit');
    Route::get('/music/{user}', EditMusic::class)->name('music.view');
    Route::get('/music/{user}/all', AllMusic::class)->name('music.all');
});
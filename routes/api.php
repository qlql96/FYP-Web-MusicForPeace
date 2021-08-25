<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\PlaylistMusicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SongMoodController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LyricSentimentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//API for android Apps....
Route::get('/getAll', [SearchController::class, 'getAll']);
Route::get('/searchAll', [SearchController::class, 'searchAll']);

//User Controller
Route::get('/loadProfileInfo', [UserController::class, 'loadProfileInfo']);
Route::get('/checkUsername', [UserController::class, 'searchUsers']);
Route::get('/getFollowers', [UserController::class, 'getFollowers']);
Route::get('/searchMusicByUserId', [UserController::class, 'searchMusicByUserId']);
Route::post('/uploadProfilePic', [UserController::class, 'uploadProfilePic']);
Route::post('/updateProfileName', [UserController::class, 'updateProfileName']);


//php artisan make:model Playlist -mc
//Music Controller
Route::get('/getTopLikesSong', [MusicController::class, 'getTopLikesSong'])->name('getTopLikesSong');
Route::get('/getTopPlaysSong', [MusicController::class, 'getTopPlaysSong'])->name('getTopPlaysSong');
Route::get('/getTopSharesSong', [MusicController::class, 'getTopSharesSong'])->name('getTopSharesSong');
Route::get('/getSongsByUserId', [MusicController::class, 'getSongsByUserId']);
Route::get('/incrementPlayOfSong', [MusicController::class, 'incrementPlayOfSong']);
Route::get('/incrementShareOfSong', [MusicController::class, 'incrementShareOfSong']);
Route::get('/getLikedSongs', [MusicController::class, 'getLikedSongs']);
Route::post('/updateSong', [MusicController::class, 'updateSong']);
Route::get('/searchMusicByAlbumId', [MusicController::class, 'searchMusicByAlbumId']);
Route::get('/searchPlaylistMusicByPlaylistId', [MusicController::class, 'searchPlaylistMusicByPlaylistId']);
Route::get('/searchMusicByGenreId', [MusicController::class, 'searchMusicByGenreId']);
Route::get('/searchMusicByThemeId', [MusicController::class, 'searchMusicByThemeId']);
Route::post('/deleteSong', [MusicController::class, 'deleteSong']);

//Genre Controller
Route::get('/getGenres', [GenreController::class, 'getGenre'])->name('getGenre');

//Theme Controller
Route::get('/getThemes', [ThemeController::class, 'getTheme'])->name('getTheme');

//Album Controller
Route::get('/getAlbumsByUserId', [AlbumController::class, 'getAlbumsByUserId']);
Route::get('/getAlbumsByAlbumId', [AlbumController::class, 'getAlbumsByAlbumId']);
Route::post('/createAlbum', [AlbumController::class, 'createAlbum']);
Route::get('/getLikedAlbums', [AlbumController::class, 'getLikedAlbums']);
Route::get('/getTopLikesAlbums', [AlbumController::class, 'getTopLikesAlbums']);
Route::get('/getTopSharesAlbums', [AlbumController::class, 'getTopSharesAlbums']);
Route::put('/addToAlbum', [AlbumController::class, 'addToAlbum']);
Route::put('/deleteAlbumMusic', [AlbumController::class, 'deleteAlbumMusic']);
Route::delete('/deleteAlbum', [AlbumController::class, 'deleteAlbum']);
Route::post('/updateAlbum', [AlbumController::class, 'updateAlbum']);
Route::get('/incrementShareOfAlbum', [AlbumController::class, 'incrementShareOfAlbum']);

//Playlist Controller
Route::get('/getPlaylistByPlaylistId', [PlaylistController::class, 'getPlaylistByPlaylistId']); 
Route::get('/searchPlaylistsByUserId', [PlaylistController::class, 'searchPlaylistsByUserId']);
Route::post('/createPlaylist', [PlaylistController::class, 'createPlaylist']);
Route::get('/getLikedPlaylists', [PlaylistController::class, 'getLikedPlaylists']);
Route::get('/getTopLikesPlaylists', [PlaylistController::class, 'getTopLikesPlaylists']);
Route::get('/getTopSharesPlaylists', [PlaylistController::class, 'getTopSharesPlaylists']);
Route::delete('/deletePlaylist', [PlaylistController::class, 'deletePlaylist']);
Route::post('/updatePlaylist', [PlaylistController::class, 'updatePlaylist']);
Route::get('/incrementShareOfPlaylist', [PlaylistController::class, 'incrementShareOfPlaylist']);

//SongMood Controller
Route::get('/getTopThemeSongByThemeId', [SongMoodController::class, 'getTopThemeSongByThemeId']);
Route::post('/addToSongMood', [SongMoodController::class, 'addToSongMood']);
Route::get('/getSongMoodsTaggedByUserId', [SongMoodController::class, 'getSongMoodsTaggedByUserId']);
Route::get('/getTopTheme', [SongMoodController::class, 'getTopTheme']);//remove

//PlaylistMusic Controller
Route::post('/addToPlaylist', [PlaylistMusicController::class, 'addToPlaylist']);
Route::delete('/deletePlaylistMusic', [PlaylistMusicController::class, 'deletePlaylistMusic']);

//Likes Controller
Route::post('/addToLikes', [LikesController::class, 'addToLikes']);
Route::delete('/deleteLikes', [LikesController::class, 'deleteLikes']);
Route::post('/addToAlbumLikes', [LikesController::class, 'addToAlbumLikes']);
Route::delete('/deleteAlbumLikes', [LikesController::class, 'deleteAlbumLikes']);
Route::post('/addToPlaylistLikes', [LikesController::class, 'addToPlaylistLikes']);
Route::delete('/deletePlaylistLikes', [LikesController::class, 'deletePlaylistLikes']);

//Follow Controller
Route::get('/getNoOfFollowerByUserId', [FollowController::class, 'getNoOfFollowerByUserId']);
Route::get('/getNoOfFollowingByUserId', [FollowController::class, 'getNoOfFollowingByUserId']);
Route::get('/getNoOfFollowerFollowingByUserId', [FollowController::class, 'getNoOfFollowerFollowingByUserId']);
Route::get('/checkFollowStatus', [FollowController::class, 'checkFollowStatus']);
Route::post('/follow', [FollowController::class, 'follow']);
Route::delete('/unfollow', [FollowController::class, 'unfollow']);

//Report Controller
Route::post('/report', [ReportController::class, 'report']);

//LyricSentiment Controller
Route::get('/getLyricSentiment', [LyricSentimentController::class, 'getLyricSentiment']);

Route::group([
    'prefix' => 'auth'],
    function (){
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/signup', [AuthController::class, 'signup']);
        Route::post('/changePassword', [AuthController::class, 'changePassword']);
        Route::group([
            'middleware' => 'auth:api'], function(){
                Route::get('/logout', [AuthController::class, 'logout']);
                //Route::get('/user', [AuthController::class, 'user']);
            });

    }
);
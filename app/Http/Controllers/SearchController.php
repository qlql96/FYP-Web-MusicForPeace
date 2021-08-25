<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    //
    const SELECTMUSICSQLCONST = 'select  *, (select username from users where id= music.userId) AS userName,
    CASE WHEN id IN (select songId from likes where userId = ?) THEN 1 ELSE 0 END as likes,
    (select albumTitle from albums where id= music.albumId) AS album,
    (select genreName from genres where id= music.genreId) AS genre,
    (select themeName from themes where id= music.themeId) AS theme 
    from music ';
    public function getAll(Request $request){
    

        $userId = $request->userId;

        $musics = DB::select(SearchController::SELECTMUSICSQLCONST, [$userId]);
        $albums = DB::select('select *, (select username from users where id= albums.userId) AS userName,
        CASE WHEN id IN (select albumId from album_likes where userId = ?) THEN 1 ELSE 0 END as likes from albums',[$userId]);
        $playlists = DB::select('select *,
        (select username from users where id = playlists.userId) AS userName, CASE WHEN id IN (select playlistId from playlist_likes where userId = ?) THEN 1 ELSE 0 END as likes from playlists', [$userId]);
        $users = DB::select('select `id`, `name`, `email`, `username`, `userPictureSrc`, `userBio` from users');
        $genres = DB::select('select * from genres');
        $themes = DB::select('select * from themes');
        //$songs = DB::table('songs')->get();
        return response()->json(['status'=> '200', 'message'=> 'Search Success','musics'=> $musics,
        'albums'=> $albums,
        'playlists'=> $playlists,
        'users'=> $users,
        'genres'=> $genres,
        'themes'=> $themes]);
        //return redirect()->back()->with('Success','User has been added successfully');
    }


    public function searchAll(Request $request){
    
        $keyword = $request->keyword;
        $userId = $request->userId;
        $keyword =  '%' . $keyword . '%';
        $musics = DB::select(SearchController::SELECTMUSICSQLCONST. ' where musicTitle like ?',[$userId,$keyword]);
        $albums = DB::select('select *,
        (select username from users where id=albums.userId) AS userName, CASE WHEN id IN (select albumId from album_likes where userId = ?) THEN 1 ELSE 0 END as likes from albums',[$userId]);
        $playlists = DB::select('select *,
        (select username from users where id=playlists.userId) AS userName,CASE WHEN id IN (select playlistId from playlist_likes where userId = ?) THEN 1 ELSE 0 END as likes from playlists', [$userId]);
        $users = DB::select('select `id`, `name`, `email`, `username`, `userPictureSrc`, `userBio` from users where username like ?',[$keyword]);
        $genres = DB::select('select * from genres where genreName like ?',[$keyword]);
        $themes = DB::select('select * from themes where themeName like ?',[$keyword]);
        //$songs = DB::table('songs')->get();
        return response()->json(['status'=> '200', 'message'=> 'Search Success','musics'=> $musics,
        'albums'=> $albums,
        'playlists'=> $playlists,
        'users'=> $users,
        'genres'=> $genres,
        'themes'=> $themes]);
        //return redirect()->back()->with('Success','User has been added successfully');
    }
}

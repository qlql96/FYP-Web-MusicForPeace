<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Likes;
use App\Models\AlbumLikes;
use App\Models\PlaylistLikes;

class LikesController extends Controller
{
    public function addToLikes(Request $request){
    
        $likes = new Likes;
        $likes->songId = $request->songId;
        $likes->userId = $request->userId;
        $likes->save();
        $songId = $request->songId;
        DB::table('music')
            ->where('id', $songId)
            ->update([
                'musicNoOfLikes' => DB::raw('musicNoOfLikes + 1'),
             ]);
         
        return response()->json(['status'=> '200', 'message'=> 'Music Liked', 'extra' => 'null']);
    }

    public function deleteLikes(Request $request){
    
        $songId = $request->songId;
        $userId = $request->userId;
        $musics = DB::delete('delete from likes where songId = ? and userId = ?', [$songId,$userId]);
        $songId = $request->songId;
        DB::table('music')
            ->where('id', $songId)
            ->where('musicNoOfLikes',">=",0)
            ->update([
                'musicNoOfLikes' => DB::raw('musicNoOfLikes - 1'),
             ]);

        return response()->json(['status'=> '200', 'message'=> 'Music unliked', 'extra'=> 'null']);
    }

    public function addToAlbumLikes(Request $request){
    
        $likes = new AlbumLikes;
        $likes->albumId = $request->albumId;
        $likes->userId = $request->userId;
        $likes->save();
        $albumId = $request->albumId;
        DB::table('albums')
            ->where('id', $albumId)
            ->update([
                'albumNoOfLikes' => DB::raw('albumNoOfLikes + 1'),
             ]);
         
        return response()->json(['status'=> '200', 'message'=> 'Album Liked', 'extra' => 'null']);
    }

    public function deleteAlbumLikes(Request $request){
    
        $albumId = $request->albumId;
        $userId = $request->userId;
        $musics = DB::delete('delete from album_likes where albumId = ? and userId = ?', [$albumId,$userId]);
        DB::table('albums')
            ->where('id', $albumId)
            ->where('albumNoOfLikes',">=",0)
            ->update([
                'albumNoOfLikes' => DB::raw('albumNoOfLikes - 1'),
             ]);

        return response()->json(['status'=> '200', 'message'=> 'Album unliked', 'extra'=> 'null']);
    }

    public function addToPlaylistLikes(Request $request){
    
        $likes = new PlaylistLikes;
        $likes->playlistId = $request->playlistId;
        $likes->userId = $request->userId;
        $likes->save();
        $playlistId = $request->playlistId;
        DB::table('playlists')
            ->where('id', $playlistId)
            ->update([
                'playlistNoOfLikes' => DB::raw('playlistNoOfLikes + 1'),
             ]);
         
        return response()->json(['status'=> '200', 'message'=> 'Playlist Liked', 'extra' => 'null']);
    }

    public function deletePlaylistLikes(Request $request){
    
        $playlistId = $request->playlistId;
        $userId = $request->userId;
        $musics = DB::delete('delete from playlist_likes where playlistId = ? and userId = ?', [$playlistId,$userId]);
        DB::table('playlists')
            ->where('id', $playlistId)
            ->where('playlistNoOfLikes',">=",0)
            ->update([
                'playlistNoOfLikes' => DB::raw('playlistNoOfLikes - 1'),
             ]);

        return response()->json(['status'=> '200', 'message'=> 'Playlist unliked', 'extra'=> 'null']);
    }
}

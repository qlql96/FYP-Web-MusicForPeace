<?php

namespace App\Http\Controllers;
 
use App\Models\PlaylistMusic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaylistMusicController extends Controller
{

    public function addToPlaylist(Request $request){
    
        $playlistMusic = new PlaylistMusic;
        $playlistMusic->songId = $request->songId;
        $playlistMusic->playlistId = $request->playlistId;
        if(!DB::table('playlist_music')->where('songId', $playlistMusic->songId)->where('playlistId', $playlistMusic->playlistId)->exists()){
            $playlistMusic->save();
            return response()->json(['status'=> '200', 'message'=> 'addToPlaylist Success', 'extra' => null]);
        }else{
            return response()->json(['status'=> '200', 'message'=> 'Music is already in the Playlist', 'extra' => null]);
        }
    }

    public function deletePlaylistMusic(Request $request){
    
        $songId = $request->songId;
        $playlistId = $request->playlistId;
        $musics = DB::delete('delete from playlist_music where songId = ? and playlistId = ?', [$songId,$playlistId]);
        return response()->json(['status'=> '200', 'message'=> 'Playlists Music Remove Success', 'songs'=> []]);
    }
}

<?php

namespace App\Http\Controllers;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaylistController extends Controller
{
    const SELECTPLAYLISTSQLCONST = 'select *, (select username from users where id= playlists.userId) AS userName,
    CASE WHEN id IN (select playlistId from playlist_likes where userId = ?) THEN 1 ELSE 0 END as likes 
    from playlists ';

    public function getTopLikesPlaylists(Request $request){
    
        $userId = $request->userId;
        $playlists = DB::select(PlaylistController::SELECTPLAYLISTSQLCONST . 'order by playlistNoOfLikes desc limit 50',[$userId]);
        return response()->json(['status'=> '200', 'message'=> 'getTopLikesPlaylists Data Retrieved','playlists'=> $playlists]);
    }

    public function getTopSharesPlaylists(Request $request){
    
        $userId = $request->userId;
        $playlists = DB::select(PlaylistController::SELECTPLAYLISTSQLCONST . 'order by playlistNoOfShares desc limit 50',[$userId]);
        return response()->json(['status'=> '200', 'message'=> 'getTopLikesPlaylists Data Retrieved','playlists'=> $playlists]);
    }


    public function searchPlaylistsByUserId(Request $request){
    
        $userId = $request->userId;
        $playlists = DB::select(PlaylistController::SELECTPLAYLISTSQLCONST .'where userId = ?',[$userId, $userId]);
        return response()->json(['status'=> '200', 'message'=> 'Search Playlists Success','playlists'=> $playlists]);
    }

    public function getPlaylistByPlaylistId(Request $request){
    
        $userId = $request->userId;
        $playlistId = $request->playlistId;
        $playlists = DB::select(PlaylistController::SELECTPLAYLISTSQLCONST.'where id = ?',[$userId, $playlistId]);
        return response()->json(['status'=> '200', 'message'=> 'Search Playlists Success','playlists'=> $playlists]);
    }

    public function getLikedPlaylists(Request $request){
    
        $userId = $request->userId;
        $playlists = DB::select('select * ,(select username from users where id=playlists.userId) AS userName, 
        1 as likes
        from playlists where id in (select playlistId from playlist_likes where userId = ?)',[$userId]);
        return response()->json(['status'=> '200', 'message'=> 'Liked Playlists Data Retrieved','playlists'=> $playlists]);
    }
  

    public function createPlaylist(Request $request){
    
        $playlists = new Playlist;
        $playlists->playlistName = $request->playlistName;
        $playlists->userId = $request->userId;
        $playlists->playlistNoOfLikes = 0;
        $playlists->playlistNoOfShares = 0;
        $playlists->save();
        
        return response()->json(['status'=> '200', 'message'=> 'createPlaylist Success', 'playlists' => $playlists]);
    }

    public function deletePlaylist(Request $request){
    
        $playlistId = $request->playlistId;
        $userId = $request->userId;
        $musics = DB::delete('delete from playlists where id = ? and userId = ?', [$playlistId,$userId]);
        return response()->json(['status'=> '200', 'message'=> 'Album deleted', 'extra'=> 'null']);
    }

    public function incrementShareOfPlaylist(Request $request){
    
        $playlistId = $request->playlistId;
        DB::table('playlists')
        ->where('id', $playlistId)
        ->update([
            'playlistNoOfShares' => DB::raw('playlistNoOfShares + 1'),
         ]);
        return response()->json(['status'=> '200', 'message'=> 'Increment Shares Success','extra'=> 'null']);
    }

    public function updatePlaylist(Request $request){

        $playlistId = $request->playlistId;
        $playlistName = $request->playlistName;
        $userId = $request->userId;
        $updated = DB::update('update playlists set playlistName = ? where id = ?', [$playlistName, $playlistId]);
        if($updated){
            return response()->json(['status'=> '200', 'message'=> 'Album Updated', 'extra' => 'Updated']);
        }else{
            return response()->json(['status'=> '200', 'message'=> 'Error in Album Update', 'extra' => 'Updated']);
        }
    }

    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Music;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MusicController extends Controller
{
    const SELECTMUSICSQLCONST = 'select  *, (select username from users where id= music.userId) AS userName,
    CASE WHEN id IN (select songId from likes where userId = ?) THEN 1 ELSE 0 END as likes,
    (select albumTitle from albums where id= music.albumId) AS album,
    (select genreName from genres where id= music.genreId) AS genre,
    (select themeName from themes where id= music.themeId) AS theme 
    from music ';

    public function getLikedSongs(Request $request){
    
        $userId = $request->userId;
        $songs = DB::select(MusicController::SELECTMUSICSQLCONST.'where id in (select songId from likes where userId = ?)',[$userId,$userId]);
        return response()->json(['status'=> '200', 'message'=> 'getTopLikesSong Data Retrieved','songs'=> $songs]);
    }

    public function getTopLikesSong(Request $request){
    
        $userId = $request->userId;
        $songs = DB::select(MusicController::SELECTMUSICSQLCONST.'order by musicNoOfLikes desc limit 50',[$userId]);
        return response()->json(['status'=> '200', 'message'=> 'getTopLikesSong Data Retrieved','songs'=> $songs]);
    }

    public function getTopPlaysSong(Request $request){
    
        $userId = $request->userId;
        $songs = DB::select(MusicController::SELECTMUSICSQLCONST.'order by musicNoOfPlays desc limit 50',[$userId]);
        return response()->json(['status'=> '200', 'message'=> 'getTopPlaysSong Data Retrieved','songs'=> $songs]);
    }

    public function getTopSharesSong(Request $request){
    
        $userId = $request->userId;
        $songs = DB::select(MusicController::SELECTMUSICSQLCONST.'order by musicNoOfShares desc limit 50',[$userId]);
        return response()->json(['status'=> '200', 'message'=> 'getTopSharesSong Data Retrieved','songs'=> $songs]);
    }

    public function getSongsByUserId(Request $request){
    
        $userId = $request->userId;
        $songs = DB::select(MusicController::SELECTMUSICSQLCONST.'WHERE userId = ?',[$userId, $userId]);
        return response()->json(['status'=> '200', 'message'=> 'Songs Data Retrieved','songs'=> $songs]);
    }

    public function incrementPlayOfSong(Request $request){
    
        $songId = $request->songId;
        DB::table('music')
        ->where('id', $songId)
        ->update([
            'musicNoOfPlays' => DB::raw('musicNoOfPlays + 1'),
         ]);
        return response()->json(['status'=> '200', 'message'=> 'Increment Plays Success','extra'=> null]);
    }

    public function incrementShareOfSong(Request $request){
    
        $songId = $request->songId;
        DB::table('music')
        ->where('id', $songId)
        ->update([
            'musicNoOfShares' => DB::raw('musicNoOfShares + 1'),
         ]);
        return response()->json(['status'=> '200', 'message'=> 'Increment Likes Success','extra'=> 'null']);
    }

    public function searchMusicByAlbumId(Request $request){
    
        $userId = $request->userId;
        $albumId = $request->albumId;
        $musics = DB::select(MusicController::SELECTMUSICSQLCONST.'where albumId = ?',[$userId,$albumId]);
        return response()->json(['status'=> '200', 'message'=> 'Search Album Music Success','songs'=> $musics]);
    }
    
    public function searchPlaylistMusicByPlaylistId(Request $request){
    
        $userId = $request->userId;
        $playlistId = $request->playlistId;
        $musics = DB::select(MusicController::SELECTMUSICSQLCONST.'where id in (select songId from playlist_music where playlistId = ?)',[$userId,$playlistId]);
        return response()->json(['status'=> '200', 'message'=> 'Search Playlists Music Success','songs'=> $musics]);
    }

    public function searchMusicByGenreId(Request $request){
    
        $userId = $request->userId;
        $genreId = $request->genreId;
        $musics = DB::select(MusicController::SELECTMUSICSQLCONST.'where genreId = ?',[$userId, $genreId]);
        return response()->json(['status'=> '200', 'message'=> 'Search Genre Music Success','songs'=> $musics]);
    }

    public function searchMusicByThemeId(Request $request){
    
        $userId = $request->userId;
        $themeId = $request->themeId;
        $musics = DB::select(MusicController::SELECTMUSICSQLCONST.'where themeId = ?',[$userId, $themeId]);
        return response()->json(['status'=> '200', 'message'=> 'Search Theme Music Success','songs'=> $musics]);
    }

    public function updateSong(Request $request){

        $email = $request->email;
        $userId = $request->userId;
        $songId = $request->songId;
        $musicTitle = $request->musicTitle;
        $genreId = $request->genreId;
        $musicYear = $request->musicYear;
        $musicLyrics = $request->musicLyrics;
        $musicLyricsWriter = $request->musicLyricsWriter;
        $musicInstrumentalMaker = $request->musicInstrumentalMaker;
        $musicProducer = $request->musicProducer;
        $musicPerformer = $request->musicPerformer;
        $songPicSrc = DB::table('music')
        ->where('id', '=', $songId)
        ->first()
        ->songPicSrc;
        $oldSongPicSrc = $songPicSrc;
        if($request->file){
            $directory = explode('/', $oldSongPicSrc)[0];
            $pathSongPic = $request->file->storePublicly('music/'.$directory,'public');
            $songPicSrc = explode('/', $pathSongPic)[1] . '/' . explode('/', $pathSongPic)[2];
        }else{
            $songPicSrc = $oldSongPicSrc;
        }
        $updated = DB::update('update music set musicTitle = ?,songPicSrc = ?, genreId = ?, musicYear = ?, musicLyrics = ?, musicLyricsWriter = ?, musicInstrumentalMaker = ?, musicProducer =?, musicPerformer =? where id = ?', [$musicTitle,$songPicSrc,$genreId,$musicYear,$musicLyrics,$musicLyricsWriter,$musicInstrumentalMaker,$musicProducer,$musicPerformer, $songId]);
        if($updated){
            if($oldSongPicSrc !=null){
                $deleted = Storage::disk('musics')->delete($oldSongPicSrc);
            }
            return response()->json(['status'=> '200', 'message'=> 'Song Updated', 'extra' => $updated]);
        }else{
            return response()->json(['status'=> '200', 'message'=> 'Error in Song Update', 'extra' => $updated]);
        }
    }

    public function deleteSong(Request $request){

        $credentials = $request->only('email', 'password');
        $songId = $request->songId;
        if(Auth::attempt($credentials)){
            $email = $request->email;
            $userId = DB::table('users')->where('email', $email)->first()->id;
            $songSrc = DB::table('music')
            ->where('id', '=', $songId)
            ->first()
            ->songSrc;
            if($songSrc){
                $deleted = Storage::disk('musics')->deleteDirectory(explode('/', $songSrc)[0]);
                $deleteMusicRecord = DB::delete('delete from music where id = ? and userId = ?',[$songId, $userId]);
                if($deleted){
                    return response()->json(['status'=> '200', 'message'=> 'Songs Deleted Success','extra'=> null]);
                }
            }
            return response()->json(['status'=> '200', 'message'=> 'Songs Deleted Unsuccess','extra'=> null]);
        }
        return response()->json(['status'=> '200', 'message'=> 'Songs Deleted Unsuccess Due to Wrong Credential','extra'=> null]);
    }
   
}

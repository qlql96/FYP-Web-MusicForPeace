<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SongMood;

class SongMoodController extends Controller
{
    
    const SELECTMUSICSQLCONST = 'select  *, (select name from users where id= music.userId) AS userName,
    CASE WHEN id IN (select songId from likes where userId = ?) THEN 1 ELSE 0 END as likes,
    (select genreName from genres where id= music.genreId) AS genre,
    (select themeName from themes where id= music.themeId) AS theme 
    from music ';

    public function getTopThemeSongByThemeId(Request $request){
    
        $userId = $request->userId;
        $themeId = $request->themeId;
       // $songs = DB::select('select songId, count(?) as tagCount from song_moods where themeId = ? group by songId order by count(themeId) desc', [$themeId, $themeId]);
        //$count = DB::select('select count(?) from song_moods 
////$songs = DB::select('select * from music where id in (select songId from song_moods where themeId = ? group by songId order by count(themeId) desc)', [$themeId]);
        $songs = DB::select('select *, (select username from users where id= music.userId) AS userName,
        CASE WHEN id IN (select songId from likes where userId = ?) THEN 1 ELSE 0 END as likes,
        (select genreName from genres where id= music.genreId) AS genre,
        (select themeName from themes where id= music.themeId) AS theme,
        (select count(themeId) from song_moods where songId = music.id) as tagCounts 
        from music where id in (select songId from song_moods where themeId = ? group by songId order by count(themeId) desc) order by tagCounts desc', [$userId, $themeId]);
    
        //$songs = DB::table('songs')->get();
        //$songs = DB::select('select * from music innerjoin (select songId from song_moods where themeId = ? group by songId order by count(themeId) desc) on music.id = song_moods.songId', [$themeId]);
        return response()->json(['status'=> '200', 'message'=> 'Songs Data Retrieved','songs'=> $songs]);
        //return redirect()->back()->with('Success','User has been added successfully');
    }

    public function getTopTheme(Request $request){


        //get the highest tag theme for that song
        $songId = $request->songId;
        $themeId = DB::table('song_moods')
        ->selectRaw('themeId, count(themeId) AS `count`')
        ->where('songId', '=', $songId)
        ->groupBy('themeId')
        ->orderBy('count', 'DESC')
        ->first()
        ->themeId;

        //update the themeId in music for that song
        DB::table('music')
        ->where('id', $songId)
        ->update([
            'themeId' => $themeId,
         ]);
        return response()->json(['status'=> '200', 'message'=> 'addToPlaylist Success', 'extra' => $themeId]);
    }


    public function addToSongMood(Request $request){
    
        $songId = $request->songId;
        $userId = $request->userId;
        DB::delete('delete from song_moods where songId = ? and userId = ?', [$songId,$userId]);
       $themeIds = explode(",", $request->themeIds);
        foreach ($themeIds as &$value) {
            $songMood = new SongMood;
            $songMood->songId = $request->songId;
            $songMood->userId = $request->userId;
            $songMood->themeId = $value;
            //check if record exists
            if(!DB::table('song_moods')->where('songId', $songMood->songId)->where('userId', $songMood->userId)->where('themeId', $value)->exists()){
                $songMood->save();
            }
        }

        //get the highest theme tags for the selected song
        //get the highest tag theme for that song
        $songId = $request->songId;
        $themeId = DB::table('song_moods')
        ->selectRaw('themeId, count(themeId) AS `count`')
        ->where('songId', '=', $songId)
        ->groupBy('themeId')
        ->orderBy('count', 'DESC')
        ->first()
        ->themeId;

        //update the themeId in music for that song
        DB::table('music')
        ->where('id', $songId)
        ->update([
            'themeId' => $themeId,
         ]);

        return response()->json(['status'=> '200', 'message'=> 'Tag themes(s) saved successfully', 'extra' => 'null']);
    }
    
    public function getSongMoodsTaggedByUserId(Request $request){
    
        $songId = $request->songId;
        $userId = $request->userId;
        $themeIds = DB::table('song_moods')->where('songId', $songId)->where('userId', $userId)->pluck('themeId');
        //$themeId = DB::select('select themeId from song_moods where songId = ? and userId = ?',[$songId, $userId])->get();
       // $themesTagged = array();
        //foreach ($themeIds as $themeId) {
         //   $themesTagged[] = $themeId;

       // }
       $total = "";
       $themeIds = $themeIds->toArray();
       foreach ($themeIds as &$value) {
            $total .=strval($value) .",";
        }
        $total = substr(trim($total), 0, -1);
      // $str1 = "a";
       //$themeIds->each(function($item, $key) {
        // Print each city name
         //   global $str1;
           // $str1 = strval($item) + $str1;
            //echo strval($item);
        //});

        return response()->json(['status'=> '200', 'message'=> 'Moods Tagged Retrieval Success', 'extra' =>  $total]);
    }
    

}

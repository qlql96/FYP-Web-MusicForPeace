<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    const SELECTALBUMSQLCONST = 'select *, (select username from users where id= albums.userId) AS userName,
    CASE WHEN id IN (select albumId from album_likes where userId = ?) THEN 1 ELSE 0 END as likes 
    from albums ';

    public function addToAlbum(Request $request){

        $albumId = $request->albumId;
        $songId = $request->songId;
        if(!DB::table('music')->where('albumId', $albumId)->where('id', $songId)->exists()){
            DB::update('update music set albumId = ? where id = ?', [$albumId , $songId]);
            return response()->json(['status'=> '200', 'message'=> 'Album Updated', 'extra' => 'Updated']);
        }else{
            return response()->json(['status'=> '200', 'message'=> 'Music is already in the album', 'extra' => 'Updated']);
        }
    }
                  
    public function getTopLikesAlbums(Request $request){
    
        $userId = $request->userId;
        $albums = DB::select(AlbumController:: SELECTALBUMSQLCONST.'order by albumNoOfLikes desc limit 50',[$userId]);
        return response()->json(['status'=> '200', 'message'=> 'getTopLikesAlbums Data Retrieved','albums'=> $albums]);
    }

    public function getTopSharesAlbums(Request $request){
    
        $userId = $request->userId;
        $albums = DB::select(AlbumController:: SELECTALBUMSQLCONST. 'order by albumNoOfShares desc limit 50',[$userId]);
        return response()->json(['status'=> '200', 'message'=> 'getTopLikesAlbums Data Retrieved','albums'=> $albums]);
    }

    public function getAlbumsByUserId(Request $request){
    
        $userId = $request->userId;
        $albums = DB::select(AlbumController:: SELECTALBUMSQLCONST. 'WHERE userId = ?',[$userId,$userId]);
        return response()->json(['status'=> '200', 'message'=> 'Albums Data Retrieved','albums'=> $albums]);
    }

    public function getAlbumsByAlbumId(Request $request){

        $userId = $request->userId;
        $albumId = $request->albumId;
        $albums = DB::select(AlbumController:: SELECTALBUMSQLCONST. 'WHERE id = ?',[$userId,$albumId]);
        return response()->json(['status'=> '200', 'message'=> 'Albums Data Retrieved','albums'=> $albums]);
    }

    public function getLikedAlbums(Request $request){
    
        $userId = $request->userId;
        $albums = DB::select('select *,(select username from users where id=albums.userId) AS userName, 
        1 as likes from albums where id in (select albumId from album_likes where userId = ?)',[$userId]);
        return response()->json(['status'=> '200', 'message'=> 'Liked Albums Data Retrieved','albums'=> $albums]);
    }

    public function createAlbum(Request $request){
        $user = DB::table('users')
        ->select('email')
        ->where('id', '=', $request->userId)
        ->first()
        ->email;
        $userId = $request->userId;
        $albumTitle = $request->albumTitle;
        $newFolder = time().rand(10,10000);
        if($request->file){
            $albumPicSrc = $request->file->store('public/albumPic/'.$userId.'/'.$newFolder);
            $albumPicSrc = explode('/', $albumPicSrc)[2] .'/'.explode('/', $albumPicSrc)[3] .'/' .explode('/', $albumPicSrc)[4];
        }else{
            $albumPicSrc = null;
        }
        $album = new Album;
        $album->albumTitle = $request->albumTitle;
        $album->albumPicSrc = $albumPicSrc;
        $album->albumYear = $request->albumYear;
        $album->userId = $request->userId;
        $album->albumNoOfLikes = 0;
        $album->albumNoOfShares = 0;
        $album->save();
        return response()->json(['status'=> '200', 'message'=> 'Create Album Successfully', 'extra' => 'Success']);
      }

    public function deleteAlbumMusic(Request $request){
    

        $albumId = $request->albumId;
        $songId = $request->songId;
        if(DB::table('music')->where('albumId', $albumId)->where('id', $songId)->exists()){
            DB::update('update music set albumId = null where id = ? and albumId = ?', [$songId, $albumId]);
            return response()->json(['status'=> '200', 'message'=> 'Music is removed from album', 'extra' => 'Updated']);
        }else{
            return response()->json(['status'=> '200', 'message'=> 'Music cannot be removed from album', 'extra' => 'Updated']);
        }

    }
    public function incrementShareOfAlbum(Request $request){
    
        $albumId = $request->albumId;
        DB::table('albums')
        ->where('id', $albumId)
        ->update([
            'albumNoOfShares' => DB::raw('albumNoOfShares + 1'),
         ]);
        return response()->json(['status'=> '200', 'message'=> 'Increment Shares Success','extra'=> 'null']);
    }

    public function deleteAlbum(Request $request){
    
        $albumId = $request->albumId;
        $userId = $request->userId;
        $albumPicSrc = DB::table('albums')
        ->where('id', '=', $albumId)
        ->where('userId', '=', $userId)
        ->first()
        ->albumPicSrc;
        if($albumPicSrc !=null){
            $deleted = Storage::disk('albums')->deleteDirectory(explode('/', $albumPicSrc)[0].'/'.explode('/', $albumPicSrc)[1]);
        }
        $musics = DB::delete('delete from albums where id = ? and userId = ?', [$albumId,$userId]);
        return response()->json(['status'=> '200', 'message'=> 'Album Deleted', 'extra'=> 'null']);
    }

    public function updateAlbum(Request $request){

        $albumId = $request->albumId;
        $albumTitle = $request->albumTitle;
        $albumYear = $request->albumYear;
        $userId = $request->userId;
        $newFolder = time().rand(10,10000);
        $albumPicSrc = DB::table('albums')
        ->where('id', '=', $albumId)
        ->where('userId', '=', $userId)
        ->first()
        ->albumPicSrc;
        $oldAlbumPicSrc = $albumPicSrc;
        if($request->file){
            $albumPicSrc = $request->file->store('public/albumPic/'.$userId.'/'.$newFolder);
            $albumPicSrc = explode('/', $albumPicSrc)[2] .'/'.explode('/', $albumPicSrc)[3] .'/' .explode('/', $albumPicSrc)[4];
        }else{
            $albumPicSrc = null;
        }
        $updated = DB::update('update albums set albumTitle = ?, albumYear = ?, albumPicSrc =? where id = ?', [$albumTitle,$albumYear, $albumPicSrc, $albumId]);
        if($updated){
            if($oldAlbumPicSrc !=null){
                $deleted = Storage::disk('albums')->deleteDirectory(explode('/', $oldAlbumPicSrc)[0].'/'.explode('/', $oldAlbumPicSrc)[1]);
            }
            return response()->json(['status'=> '200', 'message'=> 'Album Updated', 'extra' => 'Updated']);
        }else{
            return response()->json(['status'=> '200', 'message'=> 'Error in Album Update', 'extra' => 'Updated']);
        }
        
    }
}

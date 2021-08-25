<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function edit(User $user){

        return view('user.edit', compact('user'));
    }

    public function loadProfileInfo(Request $request){
    
        $userId = $request->userId;
        $user = DB::select('select name, email, username, userPictureSrc,userBio from users WHERE id = ?',[$userId]);
        return response()->json(['status'=> '200', 'message'=> 'Profile Data Retrieved', 'profile' => $user]);
    }

    public function uploadProfilePic(Request $request){

        $userId = $request->id;
        $user = DB::table('users')
        ->select('email')
        ->where('id', '=', $userId)
        ->first()
        ->email;
        $uploadedProfilePic = $request->file->store('public/profilePic/'.$user);
        $src = explode('/', $uploadedProfilePic)[2] .'/' .explode('/', $uploadedProfilePic)[3];
        DB::update('update users set userPictureSrc = ? where id = ?', [$src , $userId]);
        return response()->json(['status'=> '200', 'message'=> 'Profile Picture Successfully changed', 'extra' => $src]);
    }

    public function updateProfileName(Request $request){

        $name = $request->name;
        $bio = $request->bio;
        $userId = $request->userId;
        $updated = DB::update('update users set userBio = ?, name = ? where id = ?', [$bio,$name, $userId]);
        if($updated){
            return response()->json(['status'=> '200', 'message'=> 'Name Updated', 'extra' => 'Updated']);
        }else{
            return response()->json(['status'=> '200', 'message'=> 'Error in Profile Update', 'extra' => 'Updated']);
        }
        
    }

    public function getFollowers(Request $request){
    
        $userId = $request->userId;
        $users = DB::select('select `id`, `name`, `email`, `username`, `userPictureSrc`, `userBio` from users 
        where id in (select followingUserId from follows where followerUserId = ?)', [$userId]);
        return response()->json(['status'=> '200', 'message'=> 'Users','users'=> $users]); 
    }

    public function checkUsername(Request $request){
        if(!DB::table('users')->where('username', $request->username)->exists()){
            return response()->json(['status'=> '200', 'message'=> 'Username does not exist', 'extra' => 0]);
        }else{
            return response()->json(['status'=> '200', 'message'=> 'Username exist', 'extra' => 1]);
        }
    }

    public function searchMusicByUserId(Request $request){
    
        $userId = $request->userId;
        $songs = DB::select(MusicController::SELECTMUSICSQLCONST.'WHERE userId = ?',[$userId, $userId]);
        $albums = DB::select(AlbumController:: SELECTALBUMSQLCONST.'WHERE userId = ?',[$userId,$userId]);
        $playlists = DB::select(PlaylistController::SELECTPLAYLISTSQLCONST.'WHERE userId = ?', [$userId, $userId]);
        return response()->json(['status'=> '200', 'message'=> 'Search User Music Success','songs'=> $songs,'albums'=> $albums,'playlists'=> $playlists]);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Follow;

class FollowController extends Controller
{
    //
    public function follow(Request $request){
    
        $follow = new Follow;
        $follow->followerUserId = $request->followerUserId;
        $follow->followingUserId = $request->followingUserId;
        $follow->save();
        
        return response()->json(['status'=> '200', 'message'=> 'Followed', 'extra' => 'null']);
    }

    public function unfollow(Request $request){
    
        $followerUserId = $request->followerUserId;
        $followingUserId = $request->followingUserId;
        $follow = DB::delete('delete from follows where followerUserId = ? and followingUserId = ?', [$followerUserId,$followingUserId]);
        return response()->json(['status'=> '200', 'message'=> 'Unfollowed', 'extra'=> 'null']);
    }

    public function checkFollowStatus(Request $request){
    
        $followerUserId = $request->followerUserId;
        $followingUserId = $request->followingUserId;
          if(DB::table('follows')->where('followerUserId', $followerUserId)->where('followingUserId', $followingUserId)->exists()){
            $followStatus = 1;
        }else{
            $followStatus = 0;
        }
        return response()->json(['status'=> '200', 'message'=> 'Check Status Success','extra'=> $followStatus]);
    }

    public function getNoOfFollowerByUserId(Request $request){
    
        $followingUserId = $request->followingUserId;
        $followerCount = DB::table('follows')
        ->where('followingUserId', $followingUserId)
        ->get()
        ->count();
        return response()->json(['status'=> '200', 'message'=> 'Get no of followers success','extra'=> $followerCount]);
    }

    public function getNoOfFollowingByUserId(Request $request){
    
        $followerUserId = $request->followerUserId;
        $followingCount = DB::table('follows')
        ->where('followerUserId', $followerUserId)
        ->get()
        ->count();
        return response()->json(['status'=> '200', 'message'=> 'Get no of following success','extra'=> $followingCount]);
    }

    public function getNoOfFollowerFollowingByUserId(Request $request){
    
        $userId = $request->userId;
        $followingCount = DB::table('follows')
        ->where('followerUserId', $userId)
        ->get()
        ->count();
        $followerCount = DB::table('follows')
        ->where('followingUserId', $userId)
        ->get()
        ->count();
        $value = $followerCount . ','.$followingCount;
        return response()->json(['status'=> '200', 'message'=> 'Get no of following success','extra'=> $value]);

    }
}

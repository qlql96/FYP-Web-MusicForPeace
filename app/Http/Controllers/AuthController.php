<?php

namespace App\Http\Controllers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    //
    public function signup(Request $request){

        $request->validate([

            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();
        
        return response()->json(['status'=> '200', 'message'=> 'SignUp Success']);
    }

    public function login(Request $request){

        $request->validate([

            'email'=>'required',
            'password'=>'required'
        ]);

        $credentials = $request->only('email', 'password');

        if(!Auth::attempt($credentials)){
            return response()->json(['status'=> '401', 'message'=> 'Incorrect email or password']);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'status'=> '200', 'message'=> 'Login Success','login'=>
            ['id' =>  $user->id,
            'name' =>  $user->name,
            'username' =>  $user->username,
            'email' =>  $user->email,
            'userPictureSrc' =>  $user->userPictureSrc,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()]
        ]);
    }

    public function changePassword(Request $request){


        $credentials = $request->only('email', 'password');

        if(!Auth::attempt($credentials)){
            return response()->json(['status'=> '401', 'message'=> 'Incorrect email or password', 'extra' => 'Not Updated']);
        }

        $userId = $request->userId;
        $newPassword = bcrypt($request->newPassword);
        $updated = DB::update('update users set password = ? where id = ?', [$newPassword, $userId]);
        if($updated){
            return response()->json(['status'=> '200', 'message'=> 'Password Updated', 'extra' => 'Updated']);
        }else{
            return response()->json(['status'=> '200', 'message'=> 'Cannot Change Password', 'extra' => 'Updated']);
        }

    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request){
        return response()->json($request->user());
    }

    public function signin(Request $request){

        $request->validate([

            'email'=>'required',
            'password'=>'required'
        ]);

        /*
        $userEmail = $request->email;
        $user = DB::select('select * from users WHERE email = ?',[$userEmail]);
        //dd($user);

        if($user == null || $user[0]->password != $request->password){
            return response()->json(['status'=> '200', 'message'=> 'Email or Password Incorrect', 'auth' => []]);
        }

        return response()->json(['status'=> '200', 'message'=> 'Login Success', 'auth' => $user]);
        */

        dd(Auth::attempt([

            'email'=>$request->email,
            'password'=>$request->password
        ]));
        //{return redirect()->intended(route('getSong'));}

    
    }
}

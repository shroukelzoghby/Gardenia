<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use mysql_xdevapi\Exception;
use \Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialiteController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback(){
        try {
            $user = Socialite::driver('google')->user();
            $finduser =User::where('social_id',$user->id)->first();
            if($finduser){
                Auth::login($finduser);
                return response()->json(['status'=>'Success','msg'=>'Login Successfully','data'=>$finduser]);
            }else{
                $newUser=User::create(
                    [
                        'username'=>$user->name,
                        'email'=>$user->email,
                        'image'=>$user->avatar,
                        'social_id'=>$user->id,
                        'social_type'=>'google',
                        'password'=>Hash::make('my_google'),
                        'password_confirm'=>Hash::make('my_google'),
                        'token'=>$user->token,
                    ]
                );

               // $token = JWTAuth::fromUser($newUser);

                Auth::login($newUser);
                return response()->json(['status'=>'Success','msg'=>'Login Successfully','data'=>$newUser]);
            }


        }catch (\Exception $exception){
            return response()->json(['status'=>'Error','msg'=>$exception->getMessage()]);

        }
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        try{
            $rules=[
                'email'=>'required|email',
                'password'=>'required',
            ];


            $validator=Validator::make($request->all(),$rules);
            if($validator->fails()){
               $code=$this->returnCodeAccordingToInput($validator);
               return $this->returnValidationError($validator,$code);

            }
            $credentials=$request->only(['email','password']);
            $token=Auth::guard("api")->attempt($credentials);
            if(!$token){
                return response()->json(['status'=>'error','msg'=>'Invalid Credentials'],422);

            }
            $user=Auth::guard('api')->user();
            $user->token=$token;
            return response()->json(['status'=>'success','msg'=>'','data'=>$user],200);


        }catch (Exception $e){

            return $e->getMessage();

        }


    }

    public function register(Request $request)
    {
        $rules=[
            'username'=>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|min:6|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            'password_confirm' => 'required|same:password'
        ];
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails()){
            //$code=$this->returnCodeAccordingToInput($validator);
            //$this->returnValidationError($validator,$code);
            return response()->json(['status'=>'Error','msg'=>'Registration Failed'],422);

        }
        $user=User::create([
            'username'=>$request->username,
            'email'=>$request->email,
            'password_confirm'=>$request->password_confirm,
            'password'=>Hash::make($request->password),
        ]);
        $user->save();

        if($user){

            return $this->login($request);
        }else{
            return response()->json(['status'=>'Error','msg'=>'Registration Failed'],422);
        }

    }
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate($request->toke);
            return response()->json(['status'=>'Success','msg'=>'Successfully logged out'],200);
        }catch (JWTException $E){
            return response()->json(['status'=>'Error','msg'=>$E->getMessage()],401);

        }

    }
    public function refresh(Request $request)
    {
        $new_token= JWTAuth::refresh($request->token);
        if($new_token){
            return response()->json(['status'=>'Success','msg'=>'Successfully Refresh','data'=>$new_token]);
        }
        return response()->json(['status'=>'Error','msg'=>'Refresh token failed'],401);


    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Sanctum\Contracts\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{
    public function profile(request $request)
    {
        try {
            $id = $request->query('user_id');
           // $user = JWTAuth::parseToken()->toUser();
            $user = User::with('posts')->find($id);
            $user1=new UserResource($user);
            return response()->json(['status'=>'Success','msg'=>'user is found','data'=>$user1]);
        }catch (Exception $e){
            return response()->json(['status'=>'Error','msg' => $e->getMessage()],400);
        }


    }
    public function updateProfile(request $request){
        try {
            $user = JWTAuth::parseToken()->toUser();

            if($user){

                $validator=Validator::make($request->all(),[
                   // 'id' => 'required',
                    'username' => 'string',
                    'email' => 'string|email|',
                    'image' => 'mimes:jpeg,jpg,png|max:2048',
                    'password' => 'string|min:6',

                ]);
                if($validator->fails()){
                    return response()->json($validator->errors()->toJson(),400);
                }
                $user1= User::find($user->id);
                $user1->username=$request->username;
                $user1->email=$request->email;
                $user1->password=$request->password;
                if($request->image){
                    // Public storage with subdirectory
                    $storage = Storage::disk('public');

                  // Delete old image with subdirectory path (using path helper)
                    !is_null($user1->image) && $storage->delete(public_path('images/' . $user1->image));

                    //image name
                    $imageUrl = '/images/' . str::random(32).".".$request->image->getClientOriginalExtension();
                    $user1->image=$imageUrl ;

                    //save image in storage
                    $request->file('image')->move(public_path('images'), $imageUrl);
                }
                $user1->save();
                //$user->update($request->only('username', 'email', 'password', 'image'));
                return response()->json(['status'=>'Success','msg' => 'Profile updated successfully.','data'=>$user1],200);


            }else{
                return response()->json(['status'=>'Error','msg' => "User not authenticated"], 401);

            }

        }catch (TokenExpiredException $e){
            return response()->json(['status'=>'Error','msg' => $e->getMessage()],401);

        } catch (Exception $e){
            return response()->json(['status'=>'Error','msg' => $e->getMessage()],401);
        }

    }

}

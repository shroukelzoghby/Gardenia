<?php

namespace App\Http\Controllers\Api\V1;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ForgetPasswordRequest;
use App\Notifications\ResetPasswordVerificationNotification;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'Error','msg'=> 'The selected email is invalid.'], 422);
        }
        $input = $request->only('email');
        $user =User::where('email',$input)->first();

        $user->notify(new ResetPasswordVerificationNotification());
        return response()->json(['status'=>'Success','msg'=>'Code is send to your email'],200);
    }

}

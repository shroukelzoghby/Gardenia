<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\OptPasswordRequest;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp();
    }

    public function sendOTP(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6', // Assuming 6-digit OTP
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'Error','msg'=> $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        $otp2 = $this->otp->validate(request()->email, request()->otp);
        if (!$otp2->status) {
            return response()->json(['status'=>'Error','msg' => 'OTP is not valid'], 422);
        }
        return response()->json(['status'=>'Success','msg'=>''], 200);
    }

    public function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password'=>'required|string|min:6|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            'password_confirm' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>'Error','msg'=> $validator->errors()], 422);
        }

            $user= User::where('email',request()->email)->first();
            $user->update(['password'=>Hash::make(request()->password)]);
            $user->update(['password_confirm'=>Hash::make(request()->password_confirm)]);
            $user->tokens()->delete();
            return response()->json(['status'=>'Success','msg'=>'Reset password successfully.'],200);





    }

}

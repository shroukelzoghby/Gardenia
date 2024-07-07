<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mockery\Exception;
use Stripe;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;



class StripePaymentController extends Controller
{
    public function stripePost(Request $request)
    {
        try{

            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                $validator = Validator::make($request->all(), [
                    'amount' => 'required|numeric|min:0.01',
                    'description' => 'required|string',
                    'currency' => 'required|string|in:usd,eur,gbp,etc.',
                    'token' => 'required|string',
                ]);



                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                $amount = $request->get('amount') * 100; // Convert to cents

                try {
                    $charge = \Stripe\Charge::create([
                        'amount' => $amount,
                        'currency' => $request->get('currency'),
                        'description' => $request->get('description'),
                        'source' => $request->get('token'),
                    ]);

                    return response()->json(['message' => 'Payment successful!', 'charge_id' => $charge->id], 201);

                } catch (\Stripe\Exception\StripeException $e) {
                    report($e);
                    return response()->json(['message' => 'Payment failed: ' . $e->getMessage()], 422);
                }
            } catch (TokenExpiredException $e){
            return response()->json(['status'=>'Error','msg'=>$e->getMessage()], 401);

        }catch (Exception $e){
            return response()->json(['status'=>'Error','msg'=>$e->getMessage()], 422);

        }
    }
}

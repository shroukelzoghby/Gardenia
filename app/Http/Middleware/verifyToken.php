<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class verifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token= $request->token;
            $user= JWTAuth::parseToken()->authenticate();

        }catch (\Exception $e){
            if($e instanceof TokenInvalidException){
                return  response()->json(['msg'=>"Tokes is invalid"]);

            }elseif ($e instanceof TokenExpiredException){
                return  response()->json(['msg'=>"Tokes is expired"]);
            }else {
                return  response()->json(['msg'=>"another exception"]);
            }

        }
        return $next($request);
    }
}

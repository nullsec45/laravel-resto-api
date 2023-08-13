<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }

    public function login(){
        $this->middleware("auth:api",["except" =>["login"]]);
        auth()->shouldUse('api');
        
        $credentials=request(["email","password"]);

        if(!$token=auth()->attempt($credentials)){
            return response()->json(["error" => "Email atau Password salah!"], 401);
        }

        return $this->respondedWithToken($token);
    }

    public function me(){
        $this->middleware("auth:api",["except" =>["login"]]);
        auth()->shouldUse('api');
        
        return response()->json(auth()->user());
    }

    public function logout(){
        $this->middleware("auth:api",["except" =>["login"]]);
        auth()->shouldUse('api');

        auth()->logout();

        return response()->json(["message" => "Successfully logged out"]);
    }

    public function refresh(){
        $this->middleware("auth:api",["except" =>["login"]]);
        auth()->shouldUse('api');
        
        return $this->respondedWithToken(auth()->refresh());
    }

    public function respondedWithToken($token){
        $time_now=time();
        $time_expired=time()+3600;

        return response()->json([
            "access_token" => $token,
            "token_type" => "bearer",
            "duration_token" => auth("api")->factory()->getTTL() * 60,
            "last_login" => date("H:i:s", $time_now),
            "exipred_at" => date("H:i:s", $time_expired)
        ]);
    }
}

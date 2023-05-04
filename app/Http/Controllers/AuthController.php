<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware("auth:api",["except" =>["login"]]);
        return auth()->shouldUse('api');
    }

    public function login(){
        $credentials=request(["email","password"]);

        if(!$token=auth()->attempt($credentials)){
            return response()->json(["error" => "Unauthorized"], 401);
        }

        return $this->respondedWithToken($token);
    }

    public function me(){
        return response()->json(auth()->user());
    }

    public function logout(){
        auth()->logout();

        return response()->json(["message" => "Successfully logged out"]);
    }

    public function refresh(){
        return $this->respondedWithToken(auth()->refresh());
    }

    public function respondedWithToken($token){
        return response()->json([
            "access_token" => $token,
            "token_type" => "bearer",
            "expired_at" => auth("api")->factory()->getTTL() * 60
        ]);
    }
}

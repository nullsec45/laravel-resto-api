<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

trait AuthUserTrait{
    private function getAuthUser(){
        try{
            return auth()->userOrFail();
        }catch(\Tymon\JWTAuth\Exceptions\UserNotDefinedException $error){
            response()->json(["message" => "not authenticated, you have to login first :).", "status" => 403])->send();
            exit;
        }
    }
    
    private function checkOwnership($owner){
        $user=$this->getAuthUser();    
        if($user->id != $owner){
            response()->json(["Message" => "Not Authorized"], 403)->send();
            exit;
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\{User,Role};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
 
class RegisterController extends Controller
{
    public function register(){
        $validator=validator::make(request()->all(),[
            "username" => "required|unique:users",
            "email" => "required|email|unique:users",
            "password" => "required",
            "role_id" => "required|numeric"
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 422)->send();
        }

        $role=Role::find(request("role_id"));

        if(!$role){
             return response()->json(["message" => "Role Id Not Found!", "status" => 422], 422);
        }

        $user=User::create([
            "username" => request("username"),
            "email" => request("email"),
            "password" => Hash::make(request("password")),
            "role_id" => request("role_id")
        ]);
        
        return response()->json(["message" => "Successfully register!", "status" => 200], 200);
    }

}

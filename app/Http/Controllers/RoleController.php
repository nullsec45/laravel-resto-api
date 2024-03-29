<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $roles=Role::select()->get();

        if(count($roles) == 0){
            return response()->json(["message" => "Empty roles", "status" => 200], 200);
        }

        return response()->json($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        DB::transaction(function () {
            DB::table("roles")->insert(
                [
                   "role" => $request->role,
                ]
             );
        });

         return response()->json(["message" => "Successfully Created Role", "status" => 200], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($role)
    {
        $roles=DB::table("roles")->where("id", $role)->get();

        if(!$roles){
            return response()->json(["message" => "Role Not Found", "status" => 422], 422);
        }

        return response()->json($roles);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $role)
    {
        $this->validateRequest($request);

        $role = Role::find($role);

        if(!$role){
            return response()->json(["message" => "Role Not Found", "status" => 422], 422);
        }

        DB::transaction(function () {
            $role->update(["role" => $request->role]);
        });

        return response()->json(["message" => "Successfully Updated Role", "status" => 200], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($role)
    {
        $role = Role::find($role);

        if(!$role){
            return response()->json(["message" => "Role Not Found", "status" => 422], 422);
        }

        DB::transaction(function () {
            if($role->roles_fitur()->first()){
                foreach($role->roles_fitur()->get() as $role_fitur) {
                    $role_fitur->update(["role_id" => NULL]);
                }
            }
    
            if($role->users()->first()){
                foreach($role->users()->get() as $user) {
                    $user->update(["role_id" => NULL]);
                }
            }
           
            $role->delete();
        });
        return response()->json(["message" => "Successfully Deleted Role"]);
    }

    private function validateRequest($request){
        $rules=[
            "role" => "required|unique:roles",
        ];

       
        $validator=Validator::make($request->all(), $rules);

        if($validator->fails()){
            response()->json($validator->messages(),422)->send();
            exit;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Fitur;
use App\Models\RoleFitur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\{AuthUserTrait};
use Illuminate\Support\Facades\Validator;

class RoleFiturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use AuthUserTrait;

    public function index()
    {
        $roles_fitur=RoleFitur::select()->get();


        if(count($roles_fitur) == 0){
            return response()->json(["message" => "Empty Roles Fitur", "status" => 200], 200);
        }

        return response()->json($roles_fitur);
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

        $role_id=Role::find($request->role_id);
        $fitur_id=Fitur::find($request->fitur_id);

        if(!$role_id || !$fitur_id){
            $message=!$role_id ? "Role Not Found" : "Fitur Not Found";
            if(!$role_id && !$fitur_id){
                $message="Role and Fitur Not Found";
            }
            return response()->json(["message" => $message, "status" => 422], 422);
        }

        RoleFitur::create(["role_id" => $request->role_id, "fitur_id" => $request->fitur_id]);

        return response()->json(["message" => "Successfully Created Fitur Role", "status" => 200], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($role_fitur)
    {
        $roles_fitur=RoleFitur::find($role_fitur);

        if(!$roles_fitur){
            return response()->json(["message" => "Role Not Found", "status" => 422], 422);
        }

        return response()->json($roles_fitur);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $role_fitur)
    {
        $this->validateRequest($request);

        $role=Role::find($request->role_id);
        $fitur=Fitur::find($request->fitur_id);
        $role_fitur=RoleFitur::find($role_fitur);

        if(!$role_fitur){
            return response()->json(["message" => "Role Fitur Not Found", "status" => 422], 422);
        }

        if(!$role || !$fitur){
            $message=!$role ? "Role Not Found" : "Fitur Not Found";
            if(!$role && !$fitur){
                $message="Role and Fitur Not Found";
            }
            return response()->json(["message" => $message, "status" => 422], 422);
        }

        $role_fitur->update(["role_id" => $request->role_id, "fitur_id" => $request->fitur_id]);

        return response()->json(["message" => "Successfully Updated Role Fitur", "status" => 200], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role_fitur=RoleFitur::find($role_fitur);

        if(!$role_fitur){
            return response()->json(["message" => "Role Fitur Not Found", "status" => 422], 422);
        }
        $role_fitur->delete();
        return response()->json(["message" => "Successfully Deleted Role Fitur", "status" => 200], 200);
    }

    private function validateRequest($request){
        $rules=[
            "role_id" => "required",
            "fitur_id" => "required"
        ];

       
        $validator=Validator::make($request->all(), $rules);

        if($validator->fails()){
            response()->json($validator->messages(),422)->send();
            exit;
        }
    }
}

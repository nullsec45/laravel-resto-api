<?php

namespace App\Http\Controllers;

use App\Models\Fitur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class FiturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $fiturs=Fitur::select()->get();

        if(count($fiturs) == 0){
            return response()->json(["message" => "Empty feature", "status" => 200], 200);
        }

        return response()->json($fiturs);
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

        Fitur::create(
            [
                "fitur" => $request->fitur,
                "kode" => $request->kode,
            ]
        );

        return response()->json(["message" => "Successfully Created Fitur", "status" => 200], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $fitur)
    {
        $this->validateRequest($request,"update");
        $fitur = Fitur::find($fitur);
        
        if(!$fitur){
            return response()->json(["message" => "Fitur Not Found", "status" => 422], 422);
        }
        $fitur->fitur=$request->fitur ?? $fitur->fitur;
        $fitur->kode=$request->kode ?? $fitur->kode;

        $fitur->save();

        return response()->json(["message" => "Successfully Updated Fitur"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($fitur)
    {
        $fitur = Fitur::find($fitur);

        if(!$fitur){
            return response()->json(["message" => "Fitur Not Found", "status" => 422], 422);
        }
        
        if($fitur->roles_fitur()->first()){
            foreach($fitur->roles_fitur()->get() as $role_fitur) {
                $role_fitur->update(["fitur_id" => NULL]);
            }
        }

        $fitur->delete();
        return response()->json(["message" => "Successfully Deleted Fitur"]);
    }

    private function validateRequest($request, $type="insert"){
        $rules=[
            "fitur" => "required|unique:fiturs",
            "kode" => "required|unique:fiturs",
        ];

        if($type == "update"){
            $rules["fitur"]="unique:fiturs";
            $rules["kode"]="unique:fiturs";
        }

        $validator=Validator::make($request->all(), $rules);

        if($validator->fails()){
            response()->json($validator->messages(),422)->send();
            exit;
        }
    }
}

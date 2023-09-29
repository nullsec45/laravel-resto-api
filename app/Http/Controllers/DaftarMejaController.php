<?php

namespace App\Http\Controllers;

use App\Models\DaftarMeja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



class DaftarMejaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data=DB::table("daftar_meja")->select()->get();

        return response()->json($data);
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
            DaftarMeja::create(
            ["kode" => $request->kode, 
                "kapasitas" => $request->kapasitas,
            ]);

        });
        return response()->json(["message" => "Successfully Created Daftar Meja","status" => 200], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($kode)
    {
        $data=DaftarMeja::select()->where("kode", $kode)->first();

        if(!$data){
            return resposne()->json(["message" => "Daftar Meja Not Found","status" => 422], 422);
        }
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kode)
    {
        $DaftarMeja=DaftarMeja::find($kode);

        if(!$DaftarMeja){
            return response()->json(["message" => "Daftar Meja Not Found", 422], 422);
        }
        $kode=$request->kode;

        $this->validateRequest($request);

        DB::transaction(function () {
            $DaftarMeja->update([
                "kode" => $kode,
                "kapasitas" => $request->kapasitas
            ]);
        });
      

        $DaftarPemesan=$DaftarMeja->daftar_pemesan()->first();
        if($DaftarPemesan){
            $DaftarPemesan->kode_meja=$kode;
            $DaftarPemesan->save();
        }

        return response()->json(["message" => "Successfully Updated Daftar Meja","status" => 200], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode)
    {
        $DaftarMeja=DaftarMeja::find($kode);

        if(!$DaftarMeja){
            return response()->json(["message" => "Daftar Meja Not Found", 422], 422);
        }

        $DaftarPemesan=$DaftarMeja->daftar_pemesan()->get();
        if($DaftarPemesan){
            foreach($DaftarMeja->daftar_pemesan()->get() as $pemesan) {
                $pemesan->update(["kode_meja" => NULL]);
            }
        }

       DB::transaction(function () {
            $DaftarMeja->delete();
       });

        return response()->json(["message" => "Successfully Deleted Daftar Meja","status" => 200], 200);
    }

    private function validateRequest($request, $type="insert"){
        $rules=[
            "kode" => "required|unique:daftar_meja",
            "kapasitas" => "required|numeric",
        ];


        $validator=Validator::make($request->all(), $rules);

        if($validator->fails()){
            response()->json($validator->messages(),422)->send();
            exit;
        }
    }

    
}

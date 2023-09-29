<?php

namespace App\Http\Controllers;

use App\Models\DaftarMeja;
use Illuminate\Http\Request;
use App\Models\DaftarPemesan;
use App\Models\Scopes\IsPaidScope;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\{AuthUserTrait};
use Illuminate\Support\Facades\Validator;


class DaftarPemesanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function validateRequest($request, $type="insert"){
        $rules=[
            "kode_meja" => "required|unique:daftar_pemesan",
            "nama_pemesan" => "required",
            "total_harga" => "required|numeric"
        ];

        $validator=Validator::make($request->all(), $rules);

        if($validator->fails()){
            response()->json($validator->messages(),422)->send();
            exit;
        }
    }

    public function index()
    {  
        $data=DB::table("daftar_pemesan")->select()->get();

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
            DaftarPemesan::create(
                        ["kode_meja" => $request->kode_meja, 
                        "nama_pemesan" => $request->nama_pemesan,
                        "catatan" => $request->catatan,
                        "total_harga" => $request->total_harga
            ]);
       });

        return response()->json(["message" => "Successfully Created Daftar Pemesan","status" => 200], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($kode)
    {
        $data=DB::table("daftar_pemesan")->where("kode_pesanan", $kode)->first();

        if(!$data){
            return response()->json(["message" => "Daftar Pemesan Not Found","status" => 422], 422);
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
        $DaftarPemesan = DaftarPemesan::find($kode);
        $kode_meja=$request->kode_meja;

        $KodeMeja=DaftarMeja::find($kode_meja);
        if(!$DaftarPemesan){
            return response()->json(["message" => "Product Not Found", "status" => 422], 422);
        }
        if(!$KodeMeja){
            return response()->json(["message" => "Kode Meja Not Found", "status" => 422], 422);
        }
        $this->validateRequest($request, "update");

        DB::transaction(function () {
            $DaftarPemesan->update([
                "kode_meja" => $kode_meja,
                "nama_pemesan" => $request->nama_pemesan,
                "catatan" => $request->catatan,
                "total_harga" => $request->total_harga,
                "is_paid" => $request->is_paid
            ]); 
        });

        return response()->json(["message" => "Successfully Updated Daftar Pemesan", "status" => 200], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode)
    {
        $DaftarPemesan = DaftarPemesan::find($kode);

        if(!$DaftarPemesan){
            return response()->json(["message" => "Daftar Pemesan Not Found", "status" => 422], 422);
        }

        if($DaftarPemesan->pesanan()->first()){
            foreach($DaftarPemesan->pesanan()->get() as $pesanan){
                $pesanan->update(["kode_pesanan" => NULL]);
            }
        }
       
        DB::transaction(function () {
            $DaftarPemesan->delete();
        });
        
        return response()->json(["message" => "Successfully Deleted Daftar Pemesan", "status" => 200], 200);
    }

   
   public function daftar_pesanan(){
        $data=DaftarPemesan::query()->withoutGlobalScopes([IsPaidScope::class])->with("daftar_pesanan")->select()->get();
        return response()->json($data);
   }
}

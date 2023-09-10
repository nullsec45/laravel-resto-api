<?php

namespace App\Http\Controllers;

use App\Models\DaftarMeja;
use Illuminate\Http\Request;
use App\Models\DaftarPemesan;
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
    use AuthUserTrait;

    public function index()
    {
        auth()->shouldUse("api");
        $this->getAuthUser();
        
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
        auth()->shouldUse("api");
        $this->getAuthUser();  

        $this->validateRequest($request);

        DaftarPemesan::create(
                              ["kode_meja" => $request->kode_meja, 
                               "nama_pemesan" => $request->nama_pemesan,
                               "catatan" => $request->catatan,
                               "total_harga" => $request->total_harga
                              ]);

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
        auth()->shouldUse("api");
        $this->getAuthUser();  

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

        $DaftarPemesan->update([
            "kode_meja" => $kode_meja,
            "nama_pemesan" => $request->nama_pemesan,
            "catatan" => $request->catatan,
            "total_harga" => $request->total_harga
        ]);

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
        
        auth()->shouldUse("api");
        $this->getAuthUser();  

        $DaftarPemesan = DaftarPemesan::find($kode);


        if(!$DaftarPemesan){
            return response()->json(["message" => "Daftar Pemesan Not Found", "status" => 422], 422);
        }

        if($DaftarPemesan->pesanan()->first()){
            foreach($DaftarPemesan->pesanan()->get() as $pesanan){
                $pesanan->update(["kode_pesanan" => NULL]);
            }
        }
       
        $DaftarPemesan->delete();

        return response()->json(["message" => "Successfully Deleted Daftar Pemesan", "status" => 200], 200);
    }

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
   
}

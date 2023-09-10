<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\DaftarPemesan;
use App\Http\Controllers\{AuthUserTrait};
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    use AuthUserTrait;

    public function index(){
        auth()->shouldUse("api");
        $this->getAuthUser();  

        $data=Pesanan::select()->get();
        
        return response()->json($data);
    }
    public function store(Request $request){
        auth()->shouldUse("api");
        $this->getAuthUser();  

        $this->validateRequest($request);

        $kode_pesanan=DaftarPemesan::find($request->kode_pesanan);
        $product_id=Product::find($request->product_id);

        if(!$kode_pesanan || !$product_id){
            $message=!$kode_pesanan ? "Kode Pesanan Not Valid" : "Product Not Found";
            if(!$kode_pesanan && !$product_id){
                $message="Kode Pesanan not Valid and Product Not Found";
            }
            return response()->json(["message" => $message, "status" => 422], 422);
        }

        Pesanan::create(
                              ["kode_pesanan" => $request->kode_pesanan, 
                               "product_id" => $request->product_id,
                              ]);

        return response()->json(["message" => "Successfully Created Pesanan"]);
    }

    public function show($id){
        auth()->shouldUse("api");
        $this->getAuthUser();  

        $data=Pesanan::select()->where("id", $id);

        if(!$data){
            return response()->json(["message" => "Pesanan Not Found","status" => 422], 422);
        }
        
        return response()->json($data);
    }

    public function update($kode){
        auth()->shouldUse("api");
        $this->getAuthUser();  

        $kode_pesanan=$request->kode_pesanan;
        $Pesanan = Pesanan::select("kode_pesanan")->where("kode_pesanan", $kode)->first();
        $KodePesanan=DaftarPemesan::find($kode_pesanan);

        if(!$Pesanan){
            return response()->json(["message" => "Pesanan Not Found", "status" => 422], 422);
        }

        if(!$KodePesanan){
            return response()->json(["message" => "Kode Pesanan Not Found", "status" => 422], 422);
        }
      
        $this->validateRequest($request, "update");

        $DaftarPemesan->update([
            "kode_pesanan" => $kode_pesanan,
            "product_id" => $request->product_id,
        ]);

        return response()->json(["message" => "Successfully Updated Pesanan", "status" => 200], 200);
    }

    public function destroy(){
        auth()->shouldUse("api");
        $this->getAuthUser();  

        $Pesanan = Pesanan::find($kode);


        if(!$Pesanan){
            return response()->json(["message" => "Pesanan Not Found", "status" => 422], 422);
        }
       
        $Pesanan->delete();

        return response()->json(["message" => "Successfully Deleted Pesanan", "status" => 200], 200);
    }

    private function validateRequest($request){
        $validator=Validator::make($request->all(), [
            "kode_pesanan" => "required",
            "product_id" => "required",
        ]);

        if($validator->fails()){
            response()->json($validator->messages(), 422)->send();
            exit;
        }
    }
}

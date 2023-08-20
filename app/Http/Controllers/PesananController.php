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

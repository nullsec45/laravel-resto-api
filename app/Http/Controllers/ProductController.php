<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\{AuthUserTrait};
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use AuthUserTrait;

    public function index()
    {
        //
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

        $uploadFolder="products";
        $image=$request->file("gambar");
        $imageName="product_".strtolower($request->kode)."_".$image->hashName();
        $image->storeAs($uploadFolder, $imageName, 'public');
        Product::create(
                    [
                        "kode" => $request->kode,
                        "nama" => $request->nama,
                        "stok" => $request->stok,
                        "gambar" => $imageName,
                        "category_id" => $request->category_id
                    ]
        );

        return response()->json(["message" => "Successfully Created Product"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        auth()->shouldUse("api");
        $this->getAuthUser();  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        auth()->shouldUse("api");
        $this->getAuthUser();  
    }

    protected function validateRequest($request){
        $validator=Validator::make($request->all(),[
            "kode" => "required|unique:products",
            "nama" => "required|unique:products",
            "stok" => "required|numeric",
            "gambar" => "required|image:jpeg,png,jpg,gif,svg|max:2048",
            "category_id" => "required|numeric"
        ]);

        if($validator->fails()){
            response()->json($validator->messages(),422)->send();
            exit;
        }
    }
}

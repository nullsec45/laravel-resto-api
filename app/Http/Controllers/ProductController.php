<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\{AuthUserTrait};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


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
        $data=DB::table("products")
            ->leftJoin("categories","products.category_id","=","categories.id")
            ->select("products.id","kode","nama","stok","gambar","categories.category as category")->get();
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
                        "price"  => $request->price,
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
    public function show($product)
    {
        auth()->shouldUse("api");
        $this->getAuthUser();  

        $data=DB::table("products")->where("id", $product)->get();

        if(count($data) == 0){
            return response()->json(["message" => "Product Not Found"]);
        }
        return response()->json($data);
    }

  
    public function update(Request $request, $product)
    {
        auth()->shouldUse("api");
        $this->getAuthUser();  

        $image=$request->file("gambar");
        $uploadFolder="products";

        // $this->validateRequest($request,"update");
        $product = Product::find($product);
        $product->nama=$request->nama;
        $product->stok=$request->stok;
        if($image){
            $imageName="product_".strtolower($product->kode)."_".$image->hashName();
            $image->storeAs($uploadFolder, $imageName, 'public');
            if (file_exists("app/public/storage/products".$product->gambar)) {
                // @unlink($image_path);         
                @unlink("app/public/storage/products".$product->gambar);
            }
            $product->gambar=$imageName;
        }
        $product->price=$request->price;
        $product->category_id=$request->category_id;
        $product->save();

        return response()->json(["message" => "Successfully Updated Product"]);
        
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

    protected function validateRequest($request, $type="insert"){
        $rules=[
            "nama" => "required|unique:products",
            "kode" => "required|unique:products",
            "stok" => "required|numeric",
            "gambar" => "required|image:jpeg,png,jpg,gif,svg|max:2048",
            "price" => "required|numeric",
            "category_id" => "required|numeric"
        ];

        if($type == "update"){
            $rules["nama"]="unique:products";
            $rules["kode"]="unique:products";
            $rules["stok"]="numeric";
            $rules["gambar"]="image:jpeg,png,jpg,gif,svg|max:2048";
            $rules["price"]="numeric";
            $rules["category_id"]="numeric";
        }

        $validator=Validator::make($request->all(), $rules);

        if($validator->fails()){
            response()->json($validator->messages(),422)->send();
            exit;
        }
    }
}

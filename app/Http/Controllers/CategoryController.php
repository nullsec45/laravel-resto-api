<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Category};
use App\Http\Controllers\{AuthUserTrait};
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use AuthUserTrait;

    private function validateRequest($request){
        $validator=Validator::make($request->all(), [
            "category" => "required|unique:categories",
        ]);

        if($validator->fails()){
            response()->json($validator->messages(), 422)->send();
            exit;
        }
    }

    public function index()
    {
        auth()->shouldUse("api");
        $this->getAuthUser();  
        return response()->json(Category::all());
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

        Category::create(["category" => $request->category]);

        return response()->json(["message" => "Successfully Created Category"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($category)
    {
        auth()->shouldUse("api");
        $this->getAuthUser();  
        $category = Category::where("id",$category)->select("id")->first();
        if(!$category){
            return response()->json(["message" => "Category Not Found", "status" => 422], 422);
        }

        return response()->json(Category::with("products")->find($category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request,$category)
    {
        auth()->shouldUse("api");
        $this->getAuthUser();  

        
        $this->validateRequest($request);

        $category = Category::find($category);
        if(!$category){
            return response()->json(["message" => "Category Not Found", "status" => 422], 422);
        }
        $category->category = $request->category;
        $category->save();
        return response()->json(["message" => "Successfully Updated Category"]);
    }

  
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($category)
    {
        auth()->shouldUse("api");
        $this->getAuthUser();  

        $category=Category::where("id",$category)->select("id")->first();

        if(!$category){
            return response()->json(["message" => "Category Not Found", "status" => 422], 422);
        }
        $category->delete();

        return response()->json(["message" => "Succesfully Deleted Category","status" => 200]);
    }

    public function termahal($category){
        auth()->shouldUse("api");
        $this->getAuthUser();  

        $category=Category::find($category);

        $termahal=$category->termahal;

        return response()->json($termahal);
    }

    public function termurah($category){
        auth()->shouldUse("api");
        $this->getAuthUser();  

        $category=Category::find($category);

        $termurah=$category->termurah;

        return response()->json($termurah);
    }
    
}

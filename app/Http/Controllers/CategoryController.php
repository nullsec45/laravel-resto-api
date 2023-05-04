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
    
    public function __construct()
    {
        auth()->shouldUse("api");
        $this->getAuthUser();  
    }

    public function index()
    {
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
    public function show($id)
    {

        return response()->json(Category::all()->with("products")->find($id));
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

        $this->validateRequest($request);
        
        Category::find($id)->update([
            "category" => $request->category,
        ]);

        return response()->json(["message" => "Successfully Updated Category"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->validateRequest($request);

        $category=Category::find($id);
        $category->delete();

        return response()->json(["message" => "Succesfully deleted category :".$category->category]);
    }

    protected function validateRequest($request){
        $validator=Validator::make(request()->all(),[
            "category" => "required|unique:categories",
        ]);

        if($validator->fails()){
            return response()->json($validator->messages());
        }
    }
}

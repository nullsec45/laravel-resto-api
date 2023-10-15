<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{RegisterController,
                          CategoryController, 
                          ProductController, 
                          FiturController, 
                          AuthController, 
                          RoleController, 
                          RoleFiturController,
                          DaftarPemesanController,
                          PesananController,
                          DaftarMejaController
                        };

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(["middleware" => "api"], function($router){
    Route::prefix("auth")->group(function(){
        Route::post("register",[RegisterController::class,"register"])->name("register");
        Route::post("login",[AuthController::class,"login"])->name("login")->middleware("throttle:login");
        Route::post("logout",[AuthController::class,"logout"])->name("logout");
        Route::post("refresh",[AuthController::class,"refresh"])->name("refresh");
        Route::post("me",[AuthController::class,"me"])->name("me");
    });

    Route::group(['middleware' => ['auth-api']], function () {
        Route::get("categories/termahal/{id}",[CategoryController::class,"termahal"])->name("category.termahal");
        Route::get("categories/termurah/{id}",[CategoryController::class,"termurah"])->name("category.termurah");
    
        Route::resource('categories', CategoryController::class)->parameters([
            "category" => "id"
        ])->except(["create","edit"])->middleware("auth-api");
     
        
        Route::resource('products', ProductController::class)->parameters([
            "product" => "id"
        ])->except(["create","edit"]);
        
      
        Route::resource('fiturs', FiturController::class)->parameters([
                "fitur" => "id"
        ])->except(["create","edit"]);
    
         Route::resource('roles/fitur', RoleFiturController::class)->parameters([
            "role_fitur" => "id"
         ]);
    
        Route::resource('roles', RoleController::class)->parameters([
            "role" => "id"
        ]);
    
        Route::get("daftar-pemesan/daftar-pesanan", [DaftarPemesanController::class, "daftar_pesanan"])->name("daftar_pemesan.daftar_pesanan");
        Route::resource('daftar-pemesan', DaftarPemesanController::class)->parameters([
            "pemesan" => "kode_pesanan"
        ]);
    
        Route::resource('pesanan', PesananController::class);
    
        Route::resource('daftar-meja', DaftarMejaController::class);
    });

   
});
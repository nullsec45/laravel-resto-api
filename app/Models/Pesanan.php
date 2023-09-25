<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    // use HasFactory;

    protected $table="pesanan",
              $guarded=["id"];

   public $timestamps=false;

   public function daftar_pemesan(){
        return $this->belongsTo(Pesanan::class, "kode_pesanan");
   }

   public function product(){
    return $this->hasOne(Product::class);
   }

   
}

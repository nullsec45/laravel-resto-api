<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded=["id"];

    public  $timestamps = false;

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function termahal(){
        return $this->hasOne(Product::class,"category_id","id")->latest("price");
    }

    public function termurah(){
        return $this->hasOne(Product::class,"category_id","id")->oldest("price");
    }
}

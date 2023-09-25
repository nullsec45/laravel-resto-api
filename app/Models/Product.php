<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table="products",
              $guarded=["id"];

    public function category(){
            return $this->belongsTo(Category::class);
    }

    public function pesanan(){
        return $this->belongsTo(Pesanan::class);
    }
}

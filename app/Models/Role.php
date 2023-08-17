<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // use HasFactory;

    protected $table="roles",
              $guarded=["id"];
    
    public $timestamps=false;


    public function users() {
        return $this->hasMany(User::class);
    }

    public function roles_fitur() {
        return $this->hasMany(RoleFitur::class);
    }
}

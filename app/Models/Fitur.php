<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fitur extends Model
{
    use HasFactory;

    protected $table="fiturs",
              $guarded=["id"];

    public $timestamps=false;

    public function roles_fitur() {
        return $this->hasMany(RoleFitur::class);
    }
}

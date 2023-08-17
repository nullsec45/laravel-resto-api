<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleFitur extends Model
{
    use HasFactory;

    protected $table="roles_fitur",
              $guarded=["id"];

    public $timestamps=false;

    public function role():BelongsTo{
        return $this->belongsTo(Role::class);
    }

    public function fitur():BelongsTo{
        return $this->belongsTo(Fitur::class);
    }
}

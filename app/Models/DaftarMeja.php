<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarMeja extends Model
{
    use HasFactory;

    protected $table="daftar_meja",
              $primaryKey="kode",
              $keyType="string",
              $fillable=["kode","kapasitas"];

    public function daftar_pemesan(){
        return $this->hasOne(DaftarPemesan::class, "kode_meja");
    }
}

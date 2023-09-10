<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DaftarPemesan extends Model
{
    // use HasFactory;
    use HasUuids;

    protected $table="daftar_pemesan",
              $primaryKey="kode_pesanan",
              $keyType="string",
              $guarded=["kode_pesanan"];

    public function kode_meja(){
            return $this->belongsTo(DaftarMeja::class,"kode");
    }

    public function pesanan(){
        return $this->hasMany(Pesanan::class);
    }
}

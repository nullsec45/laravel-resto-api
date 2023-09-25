<?php

namespace App\Models;

use App\Models\Scopes\IsPaidScope;
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

    public $hidden=["created_at","updated_at"];

    public function kode_meja(){
            return $this->belongsTo(DaftarMeja::class,"kode");
    }

    public function daftar_pesanan(){
        return $this->hasMany(Pesanan::class,"kode_pesanan");
    }

    protected static function booted(){
        parent::booted();
        self::addGlobalScope(new IsPaidScope());
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class produk extends Model
{
    protected $fillable = ['nama_produk', 'kode_produk', 'harga_jual', 'stok', 'foto'];

    public function produk()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}

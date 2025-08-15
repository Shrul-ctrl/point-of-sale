<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pelanggan extends Model
{
    protected $fillable = ['nama_lengkap', 'nomor_hp', 'email'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_santri',
        'nis',
        'kelas', 
        'jenjang',
        'tahun_masuk'
    ];

    protected $casts = [
        'tahun_masuk' => 'integer'
    ];
}
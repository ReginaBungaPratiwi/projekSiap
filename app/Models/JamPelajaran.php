<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamPelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_jam',
        'jam_mulai',
        'jam_selesai',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }
}

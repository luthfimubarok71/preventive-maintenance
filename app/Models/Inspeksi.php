<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspeksi extends Model
{
    protected $fillable = [
        'segment_inspeksi',
        'jenis_jalur',
        'nama_pelaksana',
        'tanggal_inspeksi',
        'prepared_by',
        'approved_by'
    ];

    public function details()
    {
        return $this->hasMany(InspeksiDetail::class);
    }
}
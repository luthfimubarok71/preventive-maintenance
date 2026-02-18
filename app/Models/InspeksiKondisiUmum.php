<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspeksiKondisiUmum extends Model
{
    protected $table = 'inspeksi_kondisi_umum';

    protected $fillable = [
        'inspeksi_id',
        'marker_post',
        'hand_hole',
        'aksesoris_ku',
        'jc_odp'
    ];

    public function inspeksi()
    {
        return $this->belongsTo(InspeksiHeader::class, 'inspeksi_id');
    }
}
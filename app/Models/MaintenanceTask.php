<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Segment;
use App\Models\PmSchedule;
use App\Models\User;

class MaintenanceTask extends Model
{
    protected $fillable = [
        'segment_id',
        'pm_schedule_id',
        'teknisi_id',
        'tanggal_task',
        'status',
        'report',
        'signature_teknisi',
        'signature_ro',
        'signature_pusat'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI KE SEGMENT
    |--------------------------------------------------------------------------
    */

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI KE PM SCHEDULE
    |--------------------------------------------------------------------------
    */

    public function schedule()
    {
        return $this->belongsTo(PmSchedule::class, 'pm_schedule_id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI KE TEKNISI
    |--------------------------------------------------------------------------
    */

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }
}
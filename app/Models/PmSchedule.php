<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PmSchedule extends Model
{
    protected $fillable = [
        'segment_inspeksi',
        'planned_date',
        'priority',
        'status',
        'created_by',
        'approved_by',
        'signature_teknisi',
        'signature_ro',
    ];

    protected $casts = [
        'planned_date' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function inspeksiHeaders(): HasMany
    {
        return $this->hasMany(InspeksiHeader::class, 'schedule_id');
    }

    public function approvals(): HasMany
    {
        return $this->morphMany(Approval::class, 'approvable');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Helpers\EthiopianCalendar;

class Attendance extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:h:i A',
        'check_out' => 'datetime:h:i A',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected static function booted()
    {
        static::creating(function ($attendance) {
            $attendance->ethiopian_date = EthiopianCalendar::format($attendance->date);
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function scopeToday($query)
    {
        return $query->where('date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'ethiopian_date',
        'check_in',
        'check_out',
        'check_in_ip',
        'check_out_ip',
        'check_in_device',
        'check_out_device',
        'check_in_note',
        'check_out_note',
        'status',
        'late_minutes',
        'overtime_minutes',
        'worked_minutes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
        'late_minutes' => 'integer',
        'overtime_minutes' => 'integer',
        'worked_minutes' => 'integer',
    ];

    // Relations — exactly like your Employee → Department style
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Scopes — same style you use
    public function scopeToday($query)
    {
        return $query->where('date', today());
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }
}
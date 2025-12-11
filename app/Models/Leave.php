<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Leave extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'approved_at'  => 'datetime',
        'status'       => 'string',
    ];

    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        if (!$model->id) $model->id = (string) Str::uuid();

        $start = \Carbon\Carbon::parse($model->start_date);
        $end   = \Carbon\Carbon::parse($model->end_date);
        $model->total_days = $start->diffInDays($end) + 1;
    });

    static::updating(function ($model) {
        if ($model->isDirty(['start_date', 'end_date'])) {
            $start = \Carbon\Carbon::parse($model->start_date);
            $end   = \Carbon\Carbon::parse($model->end_date);
            $model->total_days = $start->diffInDays($end) + 1;
        }
    });
}

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
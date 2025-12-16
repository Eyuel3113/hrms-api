<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TrainingAttendee extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'training_attendees';

    protected $fillable = ['training_id', 'employee_id', 'status', 'attended_at', 'feedback'];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function training() { return $this->belongsTo(Training::class); }
    public function employee() { return $this->belongsTo(Employee::class); }
}
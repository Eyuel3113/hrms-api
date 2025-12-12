<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Candidate extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'job_id', 'full_name', 'email', 'phone', 'cv_path',
        'cover_letter', 'status', 'hired_at', 'hired_as_employee_id'
    ];

    protected $casts = [
        'hired_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function job() { return $this->belongsTo(Job::class); }
    public function employee() { return $this->belongsTo(Employee::class, 'hired_as_employee_id'); }
}
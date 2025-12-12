<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Job extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title', 'department_id', 'designation_id', 'description',
        'vacancy', 'deadline', 'status', 'is_active'
    ];

    protected $casts = [
        'deadline' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function department() { return $this->belongsTo(Department::class); }
    public function designation() { return $this->belongsTo(Designation::class); }
    public function candidates() { return $this->hasMany(Candidate::class); }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'open');
    }
}
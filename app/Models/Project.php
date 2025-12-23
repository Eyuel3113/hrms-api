<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Project extends Model
{
    use LogsActivity;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'status', 'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'project_members')
                    ->withPivot(['rating', 'feedback', 'rated_at'])
                    ->withTimestamps();
    }

    // Average project rating
    public function getAverageRatingAttribute()
    {
        return $this->members()->whereNotNull('rating')->avg('rating') ?? 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['title', 'status', 'start_date', 'end_date', 'is_active'])
        ->setDescriptionForEvent(fn(string $eventName) => "Project has been {$eventName}");
    }
}
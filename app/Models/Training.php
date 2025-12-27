<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Training extends Model
{
    use LogsActivity;
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'trainer_name',
        'location', 'incentive_amount', 'has_incentive', 'type',
        'is_mandatory', 'is_active'
    ];

    protected $casts = [
        'start_date'      => 'date',
        'end_date'        => 'date',
        'has_incentive'   => 'boolean',
        'is_mandatory'    => 'boolean',
        'is_active'       => 'boolean',
        'incentive_amount'=> 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function attendees()
    {
        return $this->hasMany(TrainingAttendee::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'training_attendees')
                    ->withPivot(['status', 'attended_at', 'feedback'])
                    ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title', 'description', 'start_date', 'end_date', 
                'trainer_name', 'location', 'incentive_amount', 
                'has_incentive', 'type', 'is_mandatory', 'is_active'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => 'Training created',
                'updated' => 'Training updated',
                'deleted' => 'Training deleted',
                default => "Training {$eventName}"
            })
            ->useLogName('training');
    }
}
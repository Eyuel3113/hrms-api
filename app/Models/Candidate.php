<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * Class Candidate
 *
 * @property string $id
 * @property string $job_id
 * @property string $full_name
 * @property string $email
 * @property string $phone
 * @property string $cv_path
 * @property string|null $cover_letter
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $hired_at
 * @property string|null $hired_as_employee_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Job|null $job
 * @property-read \App\Models\Employee|null $employee
 */
class Candidate extends Model
{
    use Notifiable, LogsActivity;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['status', 'hired_at', 'hired_as_employee_id'])
        ->setDescriptionForEvent(fn(string $eventName) => "Candidate has been {$eventName}");
    }
}
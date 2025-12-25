<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Notifications\Notifiable;

class Employee extends Model
{
    use SoftDeletes, LogsActivity, Notifiable;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['employee_code', 'status','shift_id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
            $model->employee_code = 'EMP-' . str_pad(Employee::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    public function personalInfo()
    {
        return $this->hasOne(EmployeePersonalInfo::class);
    }

    public function professionalInfo()
    {
        return $this->hasOne(EmployeeProfessionalInfo::class);
    }

    public function department()
    {
        return $this->hasOneThrough(Department::class, EmployeeProfessionalInfo::class, 'employee_id', 'id', 'id', 'department_id');
    }

    public function designation()
    {
        return $this->hasOneThrough(Designation::class, EmployeeProfessionalInfo::class, 'employee_id', 'id', 'id', 'designation_id');
    }
    public function shift()
{
    return $this->belongsTo(Shift::class);
}
public function trainings()
{
    return $this->belongsToMany(Training::class, 'training_attendees')
                ->withPivot('status', 'attended_at', 'feedback')
                ->withTimestamps();
}
public function projects()
{
    return $this->belongsToMany(Project::class, 'project_members')
                ->withPivot('rating', 'feedback', 'rated_at')
                ->withTimestamps();
}

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->personalInfo->email;
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['employee_code', 'status', 'shift_id'])
        ->setDescriptionForEvent(fn(string $eventName) => "Employee has been {$eventName}");
    }
}
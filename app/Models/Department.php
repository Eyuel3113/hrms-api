<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Department extends Model
{
    use HasFactory, LogsActivity;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate UUID
        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->status)) {
                $model->status = 'active';
            }
            // Auto-generate department code if not provided
            // if (empty($model->code) && !empty($model->name)) {
            //     do {
            //         $prefix = strtoupper(substr($model->name, 0, 3));
            //         $number = str_pad(random_int(1, 90), 2, '0', STR_PAD_LEFT);
            //         $code = $prefix . $number;
            //     } while (\App\Models\Department::where('code', $code)->exists());
            //     $model->code = $code;
            // }
        });
    }
    public function designations()
    {
        return $this->hasMany(Designation::class);
    }

    public function employees()
    {
        return $this->hasManyThrough(
            Employee::class,
            EmployeeProfessionalInfo::class,
            'department_id', // Foreign key on employee_professional_infos table...
            'id', // Foreign key on employees table...
            'id', // Local key on departments table...
            'employee_id' // Local key on employee_professional_infos table...
        );
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'status', 'code'])
        ->setDescriptionForEvent(fn(string $eventName) => "Department has been {$eventName}");
    }
}

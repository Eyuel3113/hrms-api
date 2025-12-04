<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Employee extends Model
{
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['employee_code', 'status'];

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
}
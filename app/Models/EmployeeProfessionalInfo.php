<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProfessionalInfo extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'employee_id', 'department_id', 'designation_id', 'joining_date', 'ending_date',
        'employment_type', 'basic_salary', 'salary_currency', 'bank_name',
        'bank_account_number', 'tax_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) \Illuminate\Support\Str::uuid());
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
}
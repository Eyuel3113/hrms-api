<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProfessionalInfo extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'employee_id', 'department_id', 'designation_id', 'joining_date', 'ending_date',
        'employment_type', 'basic_salary', 'transport_allowance', 'has_pension',
        'salary_currency', 'bank_name', 'bank_account_number', 'tax_id',
        'document_path', 'document_uploaded_at'
    ];

    protected $appends = ['document_url'];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'has_pension' => 'boolean',
        'document_uploaded_at' => 'datetime',
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

    /**
     * Get the document URL accessor.
     */
    public function getDocumentUrlAttribute()
    {
        return $this->document_path ? asset('storage/' . $this->document_path) : null;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payroll extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'employee_id', 'year', 'month', 'basic_salary', 'overtime_pay', 'holiday_pay',
        'training_incentive', 'performance_bonus', 'gross_salary', 'late_deduction',
        'absent_deduction', 'unpaid_leave_deduction', 'taxable_income', 'income_tax',
        'pension_employee', 'net_salary', 'status', 'paid_at'
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'paid_at' => 'datetime',
        'basic_salary' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'holiday_pay' => 'decimal:2',
        'training_incentive' => 'decimal:2',
        'performance_bonus' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'late_deduction' => 'decimal:2',
        'absent_deduction' => 'decimal:2',
        'unpaid_leave_deduction' => 'decimal:2',
        'taxable_income' => 'decimal:2',
        'income_tax' => 'decimal:2',
        'pension_employee' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
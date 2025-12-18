<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payroll extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'employee_id', 'month', 'year', 'basic_salary', 'transport_allowance',
        'overtime_pay', 'holiday_pay', 'incentives', 'performance_bonus',
        'late_deduction', 'absent_deduction', 'unpaid_leave_deduction',
        'pension_contribution', 'income_tax', 'gross_earnings',
        'total_deductions', 'net_pay', 'status', 'calculation_details',
        'generated_by', 'locked_at', 'locked_by'
    ];

    protected $casts = [
        'basic_salary'          => 'decimal:2',
        'transport_allowance'   => 'decimal:2',
        'overtime_pay'          => 'decimal:2',
        'holiday_pay'           => 'decimal:2',
        'incentives'            => 'decimal:2',
        'performance_bonus'     => 'decimal:2',
        'late_deduction'        => 'decimal:2',
        'absent_deduction'      => 'decimal:2',
        'unpaid_leave_deduction'=> 'decimal:2',
        'pension_contribution'  => 'decimal:2',
        'income_tax'            => 'decimal:2',
        'gross_earnings'        => 'decimal:2',
        'total_deductions'      => 'decimal:2',
        'net_pay'               => 'decimal:2',
        'calculation_details'   => 'array',
        'locked_at'             => 'datetime',
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

    public function generator()
    {
        return $this->belongsTo(Employee::class, 'generated_by');
    }

    public function locker()
    {
        return $this->belongsTo(Employee::class, 'locked_by');
    }
}

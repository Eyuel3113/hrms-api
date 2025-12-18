<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Training;
use App\Models\Project;
use App\Models\Payroll;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function generateMonthlyPayroll($month, $year)
    {
        $employees = Employee::with(['professionalInfo', 'shift'])->where('status', 'active')->get();
        $generatedPayrolls = [];

        foreach ($employees as $employee) {
            $generatedPayrolls[] = $this->calculateEmployeePayroll($employee, $month, $year);
        }

        return $generatedPayrolls;
    }

    public function calculateEmployeePayroll(Employee $employee, $month, $year)
    {
        $profInfo = $employee->professionalInfo;
        if (!$profInfo) return null;

        $basicSalary = $profInfo->basic_salary ?? 0;
        $transportAllowance = $profInfo->transport_allowance ?? 0;
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // 1. Attendance Data (Overtime, Late, Absent)
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        $overtimeMinutes = $attendances->sum('overtime_minutes');
        $lateMinutes = $attendances->sum('late_minutes');
        
        // Holiday work: 2.5x
        // We need to check if attendance.status is 'present' on a day that is in Holidays table
        $holidayDates = Holiday::active()->whereBetween('date', [$startDate, $endDate])->pluck('date')->map(fn($d) => $d->toDateString())->toArray();
        $holidayWorkMinutes = $attendances->filter(fn($a) => in_array($a->date->toDateString(), $holidayDates))->sum('worked_minutes');

        // Overtime Pay Calculation
        // Hourly rate = Basic / 200 (assuming 200 working hours/month)
        $hourlyRate = $basicSalary / 200;
        $overtimePayRegular = ($overtimeMinutes / 60) * $hourlyRate * 1.5;
        $overtimePayHoliday = ($holidayWorkMinutes / 60) * $hourlyRate * 2.5;
        
        // 2. Deductions
        $lateDeduction = ($lateMinutes / 60) * $hourlyRate;
        
        // Absent Deduction (Basic / 30 * days)
        // Note: This logic assumes 30 days month.
        $workingDaysInMonth = 22; // Or dynamic?
        $absentDays = $attendances->where('status', 'absent')->count();
        $absentDeduction = ($basicSalary / 30) * $absentDays;

        $halfDays = $attendances->where('status', 'half_day')->count();
        $halfDayDeduction = ($basicSalary / 30) * 0.5 * $halfDays;

        // 3. Unpaid Leaves
        $unpaidLeaveDays = Leave::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereHas('leaveType', fn($q) => $q->where('is_paid', false))
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate]);
            })->get()->sum('total_days');
        $unpaidLeaveDeduction = ($basicSalary / 30) * $unpaidLeaveDays;

        // 4. Training Incentives
        $trainingIncentives = $employee->trainings()
            ->wherePivot('status', 'attended')
            ->where('has_incentive', true)
            ->whereBetween('end_date', [$startDate, $endDate])
            ->sum('incentive_amount');

        // 5. Project Bonus (Rating >= 4.0)
        $avgProjectRating = DB::table('project_members')
            ->join('projects', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.employee_id', $employee->id)
            ->whereNotNull('project_members.rating')
            ->whereBetween('projects.end_date', [$startDate, $endDate])
            ->avg('rating') ?? 0;
            
        $performanceBonus = 0;
        if ($avgProjectRating >= 4.0) {
            $performanceBonus = $basicSalary * 0.10; // 10% bonus
        }

        // Totals
        $grossEarnings = $basicSalary + $transportAllowance + $overtimePayRegular + $overtimePayHoliday + $trainingIncentives + $performanceBonus;
        
        $totalDeductionsBeforeTax = $lateDeduction + $absentDeduction + $halfDayDeduction + $unpaidLeaveDeduction;
        
        // Taxable Income = Gross Earnings - Transport Allowance (partially) - Deductions?
        // In Ethiopia, Transport up to 600 is tax free.
        $taxableTransport = max(0, $transportAllowance - 600);
        $taxableIncome = ($grossEarnings - $transportAllowance) + $taxableTransport;
        
        // Deduct pre-tax deductions if applicable (unpaid leave etc usually reduce taxable income)
        $taxableIncome -= $totalDeductionsBeforeTax;

        // Progressive Tax Calculation
        $incomeTax = $this->calculateEthiopianTax($taxableIncome);
        
        // Pension 7% of Basic
        $pension = 0;
        if ($profInfo->has_pension) {
            $pension = $basicSalary * 0.07;
        }

        $totalDeductions = $totalDeductionsBeforeTax + $incomeTax + $pension;
        $netPay = $grossEarnings - $totalDeductions;

        return Payroll::updateOrCreate(
            ['employee_id' => $employee->id, 'month' => $month, 'year' => $year],
            [
                'basic_salary' => $basicSalary,
                'transport_allowance' => $transportAllowance,
                'overtime_pay' => $overtimePayRegular,
                'holiday_pay' => $overtimePayHoliday,
                'incentives' => $trainingIncentives,
                'performance_bonus' => $performanceBonus,
                'late_deduction' => $lateDeduction,
                'absent_deduction' => $absentDeduction + $halfDayDeduction,
                'unpaid_leave_deduction' => $unpaidLeaveDeduction,
                'pension_contribution' => $pension,
                'income_tax' => $incomeTax,
                'gross_earnings' => $grossEarnings,
                'total_deductions' => $totalDeductions,
                'net_pay' => $netPay,
                'status' => 'draft',
                'calculation_details' => [
                    'overtime_minutes' => $overtimeMinutes,
                    'late_minutes' => $lateMinutes,
                    'absent_days' => $absentDays,
                    'half_days' => $halfDays,
                    'unpaid_leave_days' => $unpaidLeaveDays,
                    'avg_project_rating' => $avgProjectRating,
                    'holiday_work_minutes' => $holidayWorkMinutes
                ]
            ]
        );
    }

    private function calculateEthiopianTax($income)
    {
        if ($income <= 600) return 0;
        if ($income <= 1650) return ($income * 0.10) - 60;
        if ($income <= 3200) return ($income * 0.15) - 142.5;
        if ($income <= 5250) return ($income * 0.20) - 302.50;
        if ($income <= 7800) return ($income * 0.25) - 565;
        if ($income <= 10900) return ($income * 0.30) - 955;
        return ($income * 0.35) - 1500;
    }
}

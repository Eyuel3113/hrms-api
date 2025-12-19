<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\TrainingAttendee;
use App\Models\ProjectMember;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Generate Payroll for Month
     *
     * Generate payroll for all active employees for a specific month/year.
     *
     * @group Payroll Management
     * @bodyParam year integer required Year. Example: 2025
     * @bodyParam month integer required Month (1-12). Example: 12
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $year = $request->year;
        $month = $request->month;

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $employees = Employee::where('status', 'active')
            ->with(['personalInfo', 'professionalInfo'])
            ->get();

        $generated = 0;

        foreach ($employees as $employee) {
            // Skip if payroll already exists
            if (Payroll::where('employee_id', $employee->id)->where('year', $year)->where('month', $month)->exists()) {
                continue;
            }

            $basicSalary = $employee->professionalInfo->basic_salary ?? 0;

            // Attendance
            $attendanceSummary = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->selectRaw('
                    SUM(overtime_minutes) as overtime_minutes,
                    SUM(late_minutes) as late_minutes,
                    SUM(early_leave_minutes) as early_leave_minutes,
                    COUNT(CASE WHEN status = "absent" THEN 1 END) as absent_days
                ')
                ->first();

            $overtimeMinutes = $attendanceSummary->overtime_minutes ?? 0;
            $lateMinutes = $attendanceSummary->late_minutes ?? 0;
            $absentDays = $attendanceSummary->absent_days ?? 0;

            $hourlyRate = $basicSalary / 173.33; // Ethiopian standard monthly hours

            $overtimePay = ($overtimeMinutes / 60) * $hourlyRate * 1.5; // 1.5x
            $lateDeduction = ($lateMinutes / 60) * $hourlyRate * 0.5; // 0.5x deduction

            // Holiday Work (if you have is_holiday flag in attendance)
            $holidayPay = 0; // Add logic if needed

            // Training Incentive
            $trainingIncentive = TrainingAttendee::join('trainings', 'training_attendees.training_id', '=', 'trainings.id')
                ->where('training_attendees.employee_id', $employee->id)
                ->where('training_attendees.status', 'attended')
                ->where('trainings.has_incentive', true)
                ->whereBetween('trainings.start_date', [$startDate, $endDate])
                ->sum('trainings.incentive_amount');

            // Project Performance Bonus
            $performanceRating = ProjectMember::where('employee_id', $employee->id)
                ->whereNotNull('rating')
                ->avg('rating') ?? 0;

            $performanceBonus = $performanceRating >= 4.0 ? $basicSalary * 0.10 : 0;

            // Leave Deduction
            $dailyRate = $basicSalary / 26;

            $unpaidLeaveDays = Leave::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->whereHas('leaveType', fn($q) => $q->where('is_paid', false))
                ->whereBetween('start_date', [$startDate, $endDate])
                ->sum('total_days');

            $absentDeduction = $absentDays * $dailyRate;
            $unpaidLeaveDeduction = $unpaidLeaveDays * $dailyRate;

            $grossSalary = $basicSalary + $overtimePay + $holidayPay + $trainingIncentive + $performanceBonus;

            $taxableIncome = $grossSalary - $lateDeduction - $absentDeduction - $unpaidLeaveDeduction;

            $incomeTax = $this->calculateIncomeTax($taxableIncome);

            $pension = $taxableIncome * 0.07; // 7% employee pension

            $netSalary = $taxableIncome - $incomeTax - $pension;

            Payroll::create([
                'id' => (string) Str::uuid(),
                'employee_id' => $employee->id,
                'year' => $year,
                'month' => $month,
                'basic_salary' => $basicSalary,
                'overtime_pay' => $overtimePay,
                'holiday_pay' => $holidayPay,
                'training_incentive' => $trainingIncentive,
                'performance_bonus' => $performanceBonus,
                'gross_salary' => $grossSalary,
                'late_deduction' => $lateDeduction,
                'absent_deduction' => $absentDeduction,
                'unpaid_leave_deduction' => $unpaidLeaveDeduction,
                'taxable_income' => $taxableIncome,
                'income_tax' => $incomeTax,
                'pension_employee' => $pension,
                'net_salary' => $netSalary,
                'status' => 'draft',
            ]);

            $generated++;
        }

        return response()->json([
            'message' => "Payroll generated for {$generated} employees",
            'year' => $year,
            'month' => $month,
        ]);
    }

    private function calculateIncomeTax($taxableIncome)
    {
        // Ethiopian Income Tax Brackets 2025 (simplified)
        if ($taxableIncome <= 600) return 0;
        if ($taxableIncome <= 1650) return ($taxableIncome - 600) * 0.05;
        if ($taxableIncome <= 3200) return ($taxableIncome - 1650) * 0.1 + 52.5;
        if ($taxableIncome <= 5250) return ($taxableIncome - 3200) * 0.15 + 207.5;
        if ($taxableIncome <= 7800) return ($taxableIncome - 5250) * 0.2 + 462.5;
        if ($taxableIncome <= 10900) return ($taxableIncome - 7800) * 0.25 + 972.5;
        return ($taxableIncome - 10900) * 0.3 + 1552.5;
    }

    /**
     * List Payrolls
     *
     * @group Payroll Management
     * @queryParam year integer optional
     * @queryParam month integer optional
     * @queryParam employee_id string optional
     * @queryParam status string optional draft, locked, paid
     * @queryParam limit integer optional Default 10
     */
    public function index(Request $request)
    {
        $query = Payroll::with(['employee.personalInfo']);

        if ($request->year) $query->where('year', $request->year);
        if ($request->month) $query->where('month', $request->month);
        if ($request->employee_id) $query->where('employee_id', $request->employee_id);
        if ($request->status) $query->where('status', $request->status);

        $payrolls = $query->orderByDesc('year')->orderByDesc('month')->paginate($request->limit ?? 10);

        return response()->json([
            'message' => 'Payrolls fetched',
            'data' => $payrolls->items(),
            'pagination' => [
                'total' => $payrolls->total(),
                'per_page' => $payrolls->perPage(),
                'current_page' => $payrolls->currentPage(),
                'last_page' => $payrolls->lastPage(),
            ]
        ]);
    }

    /**
     * Get Payroll by ID
     *
     * @group Payroll Management
     * @urlParam id string required Payroll UUID
     */
    public function show($id)
    {
        $payroll = Payroll::with(['employee.personalInfo'])->findOrFail($id);

        return response()->json([
            'message' => 'Payroll retrieved',
            'data' => $payroll
        ]);
    }

    /**
     * Lock Payroll
     *
     * @group Payroll Management
     * @urlParam id string required Payroll UUID
     */
    public function lock($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->status = 'locked';
        $payroll->save();

        return response()->json(['message' => 'Payroll locked']);
    }

    /**
     * Mark Payroll as Paid
     *
     * @group Payroll Management
     * @urlParam id string required Payroll UUID
     */
    public function markPaid($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->status = 'paid';
        $payroll->paid_at = now();
        $payroll->save();

        return response()->json(['message' => 'Payroll marked as paid']);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Candidate;
use App\Models\Project;
use App\Models\Payroll;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Daily Attendance Summary
     *
     * Present, Half Day, Absent percentages for today.
     *
     * @group Analytics
     */
    public function dailyAttendance()
    {
        $today = today()->toDateString();

        $totalEmployees = Employee::where('status', 'active')->count();

        if ($totalEmployees == 0) {
            return response()->json([
                'message' => 'No active employees',
                'data' => []
            ]);
        }

        $summary = Attendance::where('date', $today)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $present = $summary['present'] ?? 0;
        $halfDay = $summary['half_day'] ?? 0;
        $absent = $summary['absent'] ?? 0;

        $presentPercent = round(($present / $totalEmployees) * 100, 2);
        $halfDayPercent = round(($halfDay / $totalEmployees) * 100, 2);
        $absentPercent = round(($absent / $totalEmployees) * 100, 2);

        $data = [
            ['title' => 'Present', 'value' => $presentPercent, 'remaining' => round(100 - $presentPercent, 2)],
            ['title' => 'Half Day', 'value' => $halfDayPercent, 'remaining' => round(100 - $halfDayPercent, 2)],
            ['title' => 'Absent', 'value' => $absentPercent, 'remaining' => round(100 - $absentPercent, 2)],
        ];

        return response()->json([
            'message' => 'Daily attendance summary',
            'date' => $today,
            'total_employees' => $totalEmployees,
            'data' => $data
        ]);
    }

    /**
     * Employees by Department
     *
     * Percentage of employees in each department.
     *
     * @group Analytics
     */
    public function employeesByDepartment()
    {
        $total = Employee::where('status', 'active')->count();

        if ($total == 0) {
            return response()->json([
                'message' => 'No active employees',
                'data' => []
            ]);
        }

        $data = Employee::join('employee_professional_infos', 'employees.id', '=', 'employee_professional_infos.employee_id')
            ->join('departments', 'employee_professional_infos.department_id', '=', 'departments.id')
            ->selectRaw('departments.name, COUNT(*) as count')
            ->groupBy('departments.id', 'departments.name')
            ->get()
            ->map(function ($item) use ($total) {
                $percent = round(($item->count / $total) * 100, 2);
                return [
                    'name' => $item->name,
                    'value' => $percent
                ];
            });

        return response()->json([
            'message' => 'Employees by department',
            'total_employees' => $total,
            'data' => $data
        ]);
    }

    /**
     * Key Performance Indicators
     *
     * Total employees, candidates, projects, today's present.
     *
     * @group Analytics
     */
    public function kpi()
    {
        $today = today()->toDateString();

        $totalEmployees = Employee::count(); // all
        $activeEmployees = Employee::where('status', 'active')->count();
        $totalCandidates = Candidate::count();
        $totalProjects = Project::count();

        $todayPresent = Attendance::where('date', $today)
            ->whereIn('status', ['present', 'half_day'])
            ->distinct('employee_id')
            ->count('employee_id');

        return response()->json([
            'message' => 'Key Performance Indicators',
            'data' => [
                'total_employees' => $totalEmployees,
                'active_employees' => $activeEmployees,
                'total_candidates' => $totalCandidates,
                'total_projects' => $totalProjects,
                'today_present' => $todayPresent,
            ]
        ]);
    }

    /**
     * Payroll Outstanding — Last 8 Months
     *
     * Total net salary per month for last 8 months.
     *
     * @group Analytics
     */
    public function payrollOutstanding()
    {
        $months = Payroll::selectRaw('year, month, SUM(net_salary) as total')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->limit(8)
            ->get()
            ->map(function ($item) {
                $date = Carbon::create($item->year, $item->month, 1)->format('Y-m');
                return [
                    'date' => $date,
                    'value' => number_format($item->total, 2)
                ];
            })
            ->reverse() // oldest first
            ->values();

        // If less than 8 — fill with 0
        while ($months->count() < 8) {
            $lastDate = $months->isEmpty() 
                ? Carbon::now()->subMonth()->format('Y-m')
                : Carbon::parse($months->last()['date'])->subMonth()->format('Y-m');
            
            $months->push([
                'date' => $lastDate,
                'value' => '0.00'
            ]);
        }

        return response()->json([
            'message' => 'Payroll outstanding - last 8 months',
            'data' => $months->values()->toArray()
        ]);
    }
}
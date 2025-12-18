<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Services\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    /**
     * Generate Payroll
     * 
     * Auto generate monthly payroll for all active employees.
     * 
     * @group Payroll
     * @bodyParam month int required 1-12. Example: 12
     * @bodyParam year int required. Example: 2025
     */
    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year'  => 'required|integer',
        ]);

        $payrolls = $this->payrollService.generateMonthlyPayroll($request->month, $request->year);

        return response()->json([
            'success' => true,
            'message' => 'Payroll generated successfully for ' . $request->month . '/' . $request->year,
            'count'   => count($payrolls),
            'data'    => $payrolls
        ]);
    }

    /**
     * List Payrolls
     * 
     * @group Payroll
     * @queryParam month int
     * @queryParam year int
     * @queryParam status string
     */
    public function index(Request $request)
    {
        $query = Payroll::with(['employee.personalInfo']);

        if ($request->has('month')) $query->where('month', $request->month);
        if ($request->has('year')) $query->where('year', $request->year);
        if ($request->has('status')) $query->where('status', $request->status);

        return response()->json($query->orderBy('created_at', 'desc')->paginate($request->limit ?? 15));
    }

    /**
     * Show Payroll
     */
    public function show($id)
    {
        $payroll = Payroll::with(['employee.personalInfo', 'employee.professionalInfo'])->findOrFail($id);
        return response()->json($payroll);
    }

    /**
     * Update Payroll (Manual Override)
     * 
     * @group Payroll
     */
    public function update(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);
        
        if ($payroll->status === 'locked') {
            return response()->json(['message' => 'Payroll is locked and cannot be edited.'], 403);
        }

        $validated = $request->validate([
            'basic_salary' => 'numeric',
            'overtime_pay' => 'numeric',
            'performance_bonus' => 'numeric',
            'late_deduction' => 'numeric',
            'net_pay' => 'numeric',
            'status' => 'in:draft,locked,paid'
        ]);

        $payroll->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Payroll updated manually',
            'data' => $payroll
        ]);
    }

    /**
     * Lock Payroll
     */
    public function lock(Request $request)
    {
        $request->validate([
            'month' => 'required|integer',
            'year' => 'required|integer'
        ]);

        Payroll::where('month', $request->month)
            ->where('year', $request->year)
            ->update([
                'status' => 'locked',
                'locked_at' => now(),
                'locked_by' => auth()->id()
            ]);

        return response()->json(['message' => 'Payroll locked for ' . $request->month . '/' . $request->year]);
    }

    /**
     * Export Bank Excel (CSV)
     * 
     * Format for bulk bank transfer.
     */
    public function exportBankExcel(Request $request)
    {
        $request->validate(['month' => 'required', 'year' => 'required']);
        
        $payrolls = Payroll::with('employee.personalInfo', 'employee.professionalInfo')
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->get();

        $filename = "payroll_bank_export_{$request->year}_{$request->month}.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($payrolls) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Employee Name', 'Bank Name', 'Account Number', 'Net Pay (ETB)', 'Description']);

            foreach ($payrolls as $p) {
                fputcsv($file, [
                    $p->employee->personalInfo->first_name . ' ' . $p->employee->personalInfo->last_name,
                    $p->employee->professionalInfo->bank_name,
                    $p->employee->professionalInfo->bank_account_number,
                    $p->net_pay,
                    "Salary for {$p->month}/{$p->year}"
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download Payslip (HTML)
     * 
     * Beautiful design ready for printing.
     */
    public function downloadPayslip($id)
    {
        $payroll = Payroll::with(['employee.personalInfo', 'employee.professionalInfo', 'employee.department', 'employee.designation'])->findOrFail($id);
        
        // In a real app we'd use a PDF library. 
        // For now returning a clean HTML payslip view.
        return view('exports.payslip', compact('payroll'));
    }
}

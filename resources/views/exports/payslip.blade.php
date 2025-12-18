<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payroll->employee->personalInfo->first_name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 14px; }
        .container { width: 800px; margin: 0 auto; border: 1px solid #ccc; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .section { margin-top: 20px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .total { font-weight: bold; font-size: 16px; background-color: #eee; }
        .amharic { color: #555; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>COMPANY PAYSLIP <span class="amharic">የደመወዝ ክፍያ መግለጫ</span></h2>
            <p>Payroll for {{ $payroll->month }}/{{ $payroll->year }}</p>
        </div>

        <div class="section">
            <div class="row">
                <div>
                    <strong>Employee:</strong> {{ $payroll->employee->personalInfo->first_name }} {{ $payroll->employee->personalInfo->last_name }}<br>
                    <strong>ID:</strong> {{ $payroll->employee->employee_code }}
                </div>
                <div>
                    <strong>Department:</strong> {{ $payroll->employee->department->name ?? 'N/A' }}<br>
                    <strong>Designation:</strong> {{ $payroll->employee->designation->name ?? 'N/A' }}
                </div>
            </div>
        </div>

        <div class="section">
            <table class="table">
                <thead>
                    <tr>
                        <th>Earnings <span class="amharic">ገቢ</span></th>
                        <th>Amount (ETB)</th>
                        <th>Deductions <span class="amharic">ቅናሽ</span></th>
                        <th>Amount (ETB)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Basic Salary <span class="amharic">የመነሻ ደመወዝ</span></td>
                        <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                        <td>Income Tax <span class="amharic">የገቢ ግብር</span></td>
                        <td>{{ number_format($payroll->income_tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Transport Allowance <span class="amharic">የመጓጓዣ አበል</span></td>
                        <td>{{ number_format($payroll->transport_allowance, 2) }}</td>
                        <td>Pension (7%) <span class="amharic">ጡረታ</span></td>
                        <td>{{ number_format($payroll->pension_contribution, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Overtime <span class="amharic">ትርፍ ሰዓት</span></td>
                        <td>{{ number_format($payroll->overtime_pay + $payroll->holiday_pay, 2) }}</td>
                        <td>Late/Absent <span class="amharic">ያልተሰራበት ጊዜ</span></td>
                        <td>{{ number_format($payroll->late_deduction + $payroll->absent_deduction, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Bonuses <span class="amharic">ቦነስ</span></td>
                        <td>{{ number_format($payroll->incentives + $payroll->performance_bonus, 2) }}</td>
                        <td>Unpaid Leave <span class="amharic">ያልተከፈለበት ፈቃድ</span></td>
                        <td>{{ number_format($payroll->unpaid_leave_deduction, 2) }}</td>
                    </tr>
                    <tr class="total">
                        <td>Gross Earnings <span class="amharic">ጥቅል ገቢ</span></td>
                        <td>{{ number_format($payroll->gross_earnings, 2) }}</td>
                        <td>Total Deductions <span class="amharic">ጥቅል ቅናሽ</span></td>
                        <td>{{ number_format($payroll->total_deductions, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section" style="margin-top: 40px; text-align: center;">
            <div style="border-top: 2px solid #333; display: inline-block; width: 300px; padding-top: 5px;">
                <strong>NET PAY <span class="amharic">የተጣራ ክፍያ</span>: {{ number_format($payroll->net_pay, 2) }} ETB</strong>
            </div>
        </div>

        <div style="margin-top: 50px; display: flex; justify-content: space-around;">
            <div style="text-align: center;">
                _______________________<br>
                Employer Signature
            </div>
            <div style="text-align: center;">
                _______________________<br>
                Employee Signature
            </div>
        </div>
    </div>
</body>
</html>

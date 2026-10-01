<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Shift;
use App\Models\Employee;
use App\Models\EmployeePersonalInfo;
use App\Models\EmployeeProfessionalInfo;
use App\Models\EmployeeSocialLink;
use App\Models\Job;
use App\Models\Candidate;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\Training;
use App\Models\TrainingAttendee;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure prerequisite foundation seeders run first
        $this->call([
            AdminUserSeeder::class,
            ShiftSeeder::class,
            HolidaySeeder::class,
            LeaveTypeSeeder::class,
        ]);

        $dayShift = Shift::where('name', 'Day Shift')->first() ?? Shift::first();
        $morningShift = Shift::where('name', 'Morning Shift')->first() ?? $dayShift;

        // 2. Departments
        $departmentsData = [
            [
                'name' => 'Software Engineering',
                'code' => 'SWE',
                'description' => 'Handles web, mobile, and cloud software engineering projects',
                'status' => 'active'
            ],
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'Oversees talent acquisition, employee engagement, and workplace policies',
                'status' => 'active'
            ],
            [
                'name' => 'Finance & Accounting',
                'code' => 'FIN',
                'description' => 'Manages fiscal planning, auditing, tax filings, and payroll operations',
                'status' => 'active'
            ],
            [
                'name' => 'Marketing & Growth',
                'code' => 'MKT',
                'description' => 'Drives brand visibility, client acquisition, and digital marketing',
                'status' => 'active'
            ],
            [
                'name' => 'Operations & Logistics',
                'code' => 'OPS',
                'description' => 'Coordinates facilities, procurement, office supplies, and administrative workflow',
                'status' => 'active'
            ],
        ];

        $deptMap = [];
        foreach ($departmentsData as $dData) {
            $dept = Department::firstOrCreate(
                ['code' => $dData['code']],
                $dData
            );
            $deptMap[$dData['code']] = $dept;
        }

        // 3. Designations
        $designationsData = [
            ['title' => 'Senior Backend Engineer', 'dept' => 'SWE', 'desc' => 'Designs resilient APIs and scalable database architectures'],
            ['title' => 'Frontend Developer', 'dept' => 'SWE', 'desc' => 'Builds high performance React and Vue user interfaces'],
            ['title' => 'QA Automation Engineer', 'dept' => 'SWE', 'desc' => 'Ensures code quality and automated regression test suites'],
            ['title' => 'HR Director', 'dept' => 'HR', 'desc' => 'Leads organizational culture and strategic personnel planning'],
            ['title' => 'Talent Acquisition Specialist', 'dept' => 'HR', 'desc' => 'Recruits top-tier candidates across all engineering & business units'],
            ['title' => 'Finance Manager', 'dept' => 'FIN', 'desc' => 'Oversees treasury, budgets, reporting, and capital investments'],
            ['title' => 'Senior Accountant', 'dept' => 'FIN', 'desc' => 'Handles accounts payable, receivable, and financial compliance'],
            ['title' => 'Marketing Lead', 'dept' => 'MKT', 'desc' => 'Directs digital campaigns, brand storytelling, and user acquisition'],
            ['title' => 'Operations Manager', 'dept' => 'OPS', 'desc' => 'Manages physical and IT infrastructure and daily operations'],
        ];

        $desigMap = [];
        foreach ($designationsData as $desData) {
            $dept = $deptMap[$desData['dept']] ?? null;
            if ($dept) {
                $desig = Designation::firstOrCreate(
                    [
                        'title' => $desData['title'],
                        'department_id' => $dept->id,
                    ],
                    [
                        'description' => $desData['desc'],
                        'status' => 'active',
                    ]
                );
                $desigMap[$desData['title']] = $desig;
            }
        }

        // 4. Realistic Employees
        $employeesData = [
            [
                'first_name' => 'Eyuel',
                'last_name' => 'Endale',
                'email' => 'eyuel.endale@hrms.com',
                'phone' => '+251911234501',
                'gender' => 'male',
                'marital_status' => 'single',
                'dob' => '1993-06-15',
                'nationality' => 'Ethiopian',
                'address' => 'Bole Sub-city, Woreda 03',
                'dept' => 'SWE',
                'desig' => 'Senior Backend Engineer',
                'salary' => 58000.00,
                'bank' => 'Commercial Bank of Ethiopia',
                'acc_num' => '1000234156789',
                'shift' => $dayShift,
                'joining_date' => '2023-02-01',
            ],
            [
                'first_name' => 'Daniel',
                'last_name' => 'Yohannes',
                'email' => 'daniel.yohannes@hrms.com',
                'phone' => '+251911234502',
                'gender' => 'male',
                'marital_status' => 'single',
                'dob' => '1995-11-20',
                'nationality' => 'Ethiopian',
                'address' => 'Kazanchis, Kirkos Sub-city',
                'dept' => 'SWE',
                'desig' => 'Frontend Developer',
                'salary' => 52000.00,
                'bank' => 'Dashen Bank',
                'acc_num' => '5100987654321',
                'shift' => $dayShift,
                'joining_date' => '2023-04-15',
            ],
            [
                'first_name' => 'Genet',
                'last_name' => 'Solomon',
                'email' => 'genet.solomon@hrms.com',
                'phone' => '+251911234503',
                'gender' => 'female',
                'marital_status' => 'married',
                'dob' => '1989-03-10',
                'nationality' => 'Ethiopian',
                'address' => 'CMC, Yeka Sub-city',
                'dept' => 'HR',
                'desig' => 'HR Director',
                'salary' => 64000.00,
                'bank' => 'Awash Bank',
                'acc_num' => '0142981234567',
                'shift' => $dayShift,
                'joining_date' => '2022-09-01',
            ],
            [
                'first_name' => 'Abebe',
                'last_name' => 'Kebede',
                'email' => 'abebe.kebede@hrms.com',
                'phone' => '+251911234504',
                'gender' => 'male',
                'marital_status' => 'married',
                'dob' => '1987-08-25',
                'nationality' => 'Ethiopian',
                'address' => 'Sarbet, Nifas Silk Lafto',
                'dept' => 'FIN',
                'desig' => 'Finance Manager',
                'salary' => 68000.00,
                'bank' => 'Commercial Bank of Ethiopia',
                'acc_num' => '1000876543210',
                'shift' => $dayShift,
                'joining_date' => '2022-05-10',
            ],
            [
                'first_name' => 'Selamawit',
                'last_name' => 'Tadesse',
                'email' => 'selamawit.tadesse@hrms.com',
                'phone' => '+251911234505',
                'gender' => 'female',
                'marital_status' => 'single',
                'dob' => '1996-02-14',
                'nationality' => 'Ethiopian',
                'address' => 'Gerji, Bole Sub-city',
                'dept' => 'SWE',
                'desig' => 'QA Automation Engineer',
                'salary' => 45000.00,
                'bank' => 'Bank of Abyssinia',
                'acc_num' => '8901234567890',
                'shift' => $morningShift,
                'joining_date' => '2024-01-10',
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael.brown@hrms.com',
                'phone' => '+251911234506',
                'gender' => 'male',
                'marital_status' => 'married',
                'dob' => '1991-09-05',
                'nationality' => 'American',
                'address' => 'Old Airport, Lideta Sub-city',
                'dept' => 'MKT',
                'desig' => 'Marketing Lead',
                'salary' => 55000.00,
                'bank' => 'Commercial Bank of Ethiopia',
                'acc_num' => '1000456789012',
                'shift' => $dayShift,
                'joining_date' => '2023-10-01',
            ],
        ];

        $createdEmployees = [];
        foreach ($employeesData as $idx => $eData) {
            $existingPersonal = EmployeePersonalInfo::where('email', $eData['email'])->first();
            
            if ($existingPersonal && $existingPersonal->employee) {
                $emp = $existingPersonal->employee;
            } else {
                $emp = Employee::create([
                    'status' => 'active',
                    'shift_id' => $eData['shift'] ? $eData['shift']->id : null,
                ]);

                $emp->personalInfo()->create([
                    'first_name' => $eData['first_name'],
                    'last_name' => $eData['last_name'],
                    'email' => $eData['email'],
                    'phone' => $eData['phone'],
                    'date_of_birth' => $eData['dob'],
                    'gender' => $eData['gender'],
                    'marital_status' => $eData['marital_status'],
                    'nationality' => $eData['nationality'],
                    'address' => $eData['address'],
                    'city' => 'Addis Ababa',
                    'state' => 'Addis Ababa',
                    'zip_code' => '1000',
                ]);

                $dept = $deptMap[$eData['dept']] ?? null;
                $desig = $desigMap[$eData['desig']] ?? null;

                $emp->professionalInfo()->create([
                    'department_id' => $dept ? $dept->id : null,
                    'designation_id' => $desig ? $desig->id : null,
                    'joining_date' => $eData['joining_date'],
                    'employment_type' => 'full-time',
                    'basic_salary' => $eData['salary'],
                    'transport_allowance' => 2000.00,
                    'has_pension' => true,
                    'salary_currency' => 'ETB',
                    'bank_name' => $eData['bank'],
                    'bank_account_number' => $eData['acc_num'],
                    'tax_id' => 'TIN-' . (10000000 + $idx),
                ]);

                $emp->socialLinks()->create([
                    'platform' => 'linkedin',
                    'url' => 'https://linkedin.com/in/' . strtolower($eData['first_name'] . '-' . $eData['last_name']),
                ]);
            }

            $createdEmployees[] = $emp;
        }

        // 5. Job Postings
        $jobsData = [
            [
                'title' => 'Senior Full-Stack Developer',
                'dept' => 'SWE',
                'desig' => 'Senior Backend Engineer',
                'desc' => 'We are seeking an experienced Full Stack engineer with strong Laravel & React foundations to lead core platform modules.',
                'vacancy' => 2,
                'min_salary' => 50000.00,
                'max_salary' => 70000.00,
            ],
            [
                'title' => 'UI/UX Product Designer',
                'dept' => 'SWE',
                'desig' => 'Frontend Developer',
                'desc' => 'Looking for an inventive UI/UX designer capable of turning complex HR operations into intuitive and elegant web workflows.',
                'vacancy' => 1,
                'min_salary' => 35000.00,
                'max_salary' => 50000.00,
            ],
            [
                'title' => 'Talent Acquisition Specialist',
                'dept' => 'HR',
                'desig' => 'Talent Acquisition Specialist',
                'desc' => 'Responsible for candidate sourcing, technical interviews, and onboarding our growing engineering workforce.',
                'vacancy' => 1,
                'min_salary' => 28000.00,
                'max_salary' => 40000.00,
            ],
            [
                'title' => 'Senior Accountant & Auditor',
                'dept' => 'FIN',
                'desig' => 'Senior Accountant',
                'desc' => 'Seeking an accurate and diligent senior accountant to manage financial compliance, reconciliation, and audit reporting.',
                'vacancy' => 1,
                'min_salary' => 32000.00,
                'max_salary' => 48000.00,
            ],
        ];

        $createdJobs = [];
        foreach ($jobsData as $jData) {
            $dept = $deptMap[$jData['dept']] ?? null;
            $desig = $desigMap[$jData['desig']] ?? null;

            $job = Job::firstOrCreate(
                ['title' => $jData['title']],
                [
                    'department_id' => $dept ? $dept->id : null,
                    'designation_id' => $desig ? $desig->id : null,
                    'description' => $jData['desc'],
                    'vacancy' => $jData['vacancy'],
                    'deadline' => Carbon::now()->addDays(30),
                    'status' => 'open',
                    'is_active' => true,
                    'min_salary' => $jData['min_salary'],
                    'max_salary' => $jData['max_salary'],
                    'salary_currency' => 'ETB',
                    'salary_negotiable' => true,
                    'show_salary' => true,
                ]
            );
            $createdJobs[] = $job;
        }

        // 6. Candidates
        if (count($createdJobs) > 0) {
            $firstJob = $createdJobs[0];
            $candidatesData = [
                [
                    'job_id' => $firstJob->id,
                    'full_name' => 'Biruk Hailu',
                    'email' => 'biruk.hailu@example.com',
                    'phone' => '+251921345678',
                    'cv_path' => 'candidates/cv/demo_cv_biruk.pdf',
                    'cover_letter' => 'Passionate full-stack developer with 5 years of production experience in Laravel, PostgreSQL, and React.',
                    'status' => 'interviewed',
                ],
                [
                    'job_id' => $firstJob->id,
                    'full_name' => 'Hanna Bekele',
                    'email' => 'hanna.bekele@example.com',
                    'phone' => '+251932456789',
                    'cv_path' => 'candidates/cv/demo_cv_hanna.pdf',
                    'cover_letter' => 'Experienced software engineer looking to contribute to high impact cloud software products.',
                    'status' => 'shortlisted',
                ],
                [
                    'job_id' => $firstJob->id,
                    'full_name' => 'Yonas Assefa',
                    'email' => 'yonas.assefa@example.com',
                    'phone' => '+251943567890',
                    'cv_path' => 'candidates/cv/demo_cv_yonas.pdf',
                    'cover_letter' => 'Recent graduate with strong backend fundamentals and open-source contributions.',
                    'status' => 'new',
                ]
            ];

            foreach ($candidatesData as $cData) {
                Candidate::firstOrCreate(
                    ['email' => $cData['email'], 'job_id' => $cData['job_id']],
                    $cData
                );
            }
        }

        // 7. Projects & Members
        $project1 = Project::firstOrCreate(
            ['title' => 'Enterprise HRMS Modernization'],
            [
                'description' => 'Complete rebuild and deployment of HRMS core modules, automated payroll, and attendance tracking.',
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(4),
                'status' => 'in_progress',
                'is_active' => true,
            ]
        );

        $project2 = Project::firstOrCreate(
            ['title' => 'Mobile Banking API Gateway'],
            [
                'description' => 'Secure microservices gateway enabling external fintech integrations and transaction handling.',
                'start_date' => Carbon::now()->subMonth(),
                'end_date' => Carbon::now()->addMonths(2),
                'status' => 'in_progress',
                'is_active' => true,
            ]
        );

        if (count($createdEmployees) >= 3) {
            ProjectMember::firstOrCreate(
                ['project_id' => $project1->id, 'employee_id' => $createdEmployees[0]->id],
                [
                    'rating' => 4.85,
                    'feedback' => 'Exceptional architecture design and on-schedule API delivery.',
                    'rated_at' => Carbon::now()->subDays(5),
                ]
            );

            ProjectMember::firstOrCreate(
                ['project_id' => $project1->id, 'employee_id' => $createdEmployees[1]->id],
                [
                    'rating' => 4.70,
                    'feedback' => 'Built beautiful responsive UI dashboards with seamless state management.',
                    'rated_at' => Carbon::now()->subDays(4),
                ]
            );

            ProjectMember::firstOrCreate(
                ['project_id' => $project2->id, 'employee_id' => $createdEmployees[0]->id],
                [
                    'rating' => 4.90,
                    'feedback' => 'Bulletproof authentication implementation and comprehensive tests.',
                    'rated_at' => Carbon::now()->subDays(2),
                ]
            );
        }

        // 8. Leave Requests
        $annualLeaveType = LeaveType::where('name', 'Annual Leave')->first() ?? LeaveType::first();
        $sickLeaveType = LeaveType::where('name', 'Sick Leave')->first() ?? $annualLeaveType;

        if ($annualLeaveType && count($createdEmployees) >= 3) {
            $hrDirector = $createdEmployees[2]; // Genet Solomon

            // Approved Leave
            Leave::firstOrCreate(
                [
                    'employee_id' => $createdEmployees[1]->id, // Daniel Yohannes
                    'start_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                ],
                [
                    'leave_type_id' => $annualLeaveType->id,
                    'end_date' => Carbon::now()->subDays(7)->format('Y-m-d'),
                    'total_days' => 4,
                    'reason' => 'Annual family vacation in Bahir Dar.',
                    'status' => 'approved',
                    'approved_by' => $hrDirector->id,
                    'approved_at' => Carbon::now()->subDays(11),
                ]
            );

            // Pending Leave
            Leave::firstOrCreate(
                [
                    'employee_id' => $createdEmployees[0]->id, // Eyuel Endale
                    'start_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                ],
                [
                    'leave_type_id' => $annualLeaveType->id,
                    'end_date' => Carbon::now()->addDays(9)->format('Y-m-d'),
                    'total_days' => 5,
                    'reason' => 'Attending international developer conference and personal time.',
                    'status' => 'pending',
                ]
            );

            // Sick Leave
            if ($sickLeaveType) {
                Leave::firstOrCreate(
                    [
                        'employee_id' => $createdEmployees[4]->id, // Selamawit Tadesse
                        'start_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                    ],
                    [
                        'leave_type_id' => $sickLeaveType->id,
                        'end_date' => Carbon::now()->subDays(14)->format('Y-m-d'),
                        'total_days' => 2,
                        'reason' => 'Medical recovery and doctor recommended rest.',
                        'status' => 'approved',
                        'approved_by' => $hrDirector->id,
                        'approved_at' => Carbon::now()->subDays(16),
                    ]
                );
            }
        }

        // 9. Attendance records (Past 5 work days for employees)
        foreach ($createdEmployees as $emp) {
            for ($daysAgo = 5; $daysAgo >= 1; $daysAgo--) {
                $targetDate = Carbon::now()->subDays($daysAgo);
                
                // Skip weekends
                if ($targetDate->isWeekend()) {
                    continue;
                }

                $dateStr = $targetDate->format('Y-m-d');
                $isLate = ($daysAgo === 2 && $emp->id === $createdEmployees[1]->id);

                $checkInTime = $isLate ? '09:25:00' : '08:52:00';
                $checkOutTime = '17:35:00';
                $status = $isLate ? 'late' : 'present';
                $lateMinutes = $isLate ? 25 : 0;
                $workedMinutes = 480 - $lateMinutes;

                Attendance::firstOrCreate(
                    [
                        'employee_id' => $emp->id,
                        'date' => $dateStr,
                    ],
                    [
                        'check_in' => $checkInTime,
                        'check_out' => $checkOutTime,
                        'check_in_ip' => '196.188.12.44',
                        'check_out_ip' => '196.188.12.44',
                        'check_in_device' => 'Web App',
                        'check_out_device' => 'Web App',
                        'status' => $status,
                        'late_minutes' => $lateMinutes,
                        'early_leave_minutes' => 0,
                        'overtime_minutes' => 0,
                        'worked_minutes' => $workedMinutes,
                        'note' => $isLate ? 'Traffic delay on ring road' : 'Standard work shift',
                    ]
                );
            }
        }

        // 10. Payroll Records (Previous Month)
        $payrollYear = Carbon::now()->subMonth()->year;
        $payrollMonth = Carbon::now()->subMonth()->month;

        foreach ($createdEmployees as $emp) {
            $basic = $emp->professionalInfo ? (float)$emp->professionalInfo->basic_salary : 45000.00;
            $transport = 2000.00;
            $gross = $basic + $transport;
            $tax = round($gross * 0.20, 2); // 20% estimated
            $pension = round($basic * 0.07, 2); // 7% employee pension
            $net = $gross - ($tax + $pension);

            Payroll::firstOrCreate(
                [
                    'employee_id' => $emp->id,
                    'year' => $payrollYear,
                    'month' => $payrollMonth,
                ],
                [
                    'basic_salary' => $basic,
                    'overtime_pay' => 0.00,
                    'holiday_pay' => 0.00,
                    'training_incentive' => 0.00,
                    'performance_bonus' => 1500.00,
                    'gross_salary' => $gross + 1500.00,
                    'late_deduction' => 0.00,
                    'absent_deduction' => 0.00,
                    'unpaid_leave_deduction' => 0.00,
                    'taxable_income' => $gross + 1500.00,
                    'income_tax' => $tax,
                    'pension_employee' => $pension,
                    'net_salary' => $net + 1500.00,
                    'status' => 'paid',
                    'paid_at' => Carbon::now()->subMonth()->endOfMonth(),
                ]
            );
        }

        // 11. Trainings & Attendees
        $training1 = Training::firstOrCreate(
            ['title' => 'Advanced Cloud Architecture & Containerization'],
            [
                'description' => 'Hands-on workshop covering Docker multi-stage builds, cloud scaling, and microservices design.',
                'start_date' => Carbon::now()->subWeeks(3),
                'end_date' => Carbon::now()->subWeeks(3)->addDays(3),
                'trainer_name' => 'Tech Leads Guild',
                'location' => 'Training Room A / Hybrid Online',
                'incentive_amount' => 1000.00,
                'has_incentive' => true,
                'type' => 'internal',
                'is_mandatory' => true,
                'is_active' => true,
            ]
        );

        $training2 = Training::firstOrCreate(
            ['title' => 'Workplace Safety & Labor Compliance 2026'],
            [
                'description' => 'Annual regulatory compliance, occupational health, and data privacy protocols.',
                'start_date' => Carbon::now()->addWeeks(2),
                'end_date' => Carbon::now()->addWeeks(2)->addDay(),
                'trainer_name' => 'Ministry of Labor Certified Instructor',
                'location' => 'Main Auditorium',
                'incentive_amount' => 0.00,
                'has_incentive' => false,
                'type' => 'compliance',
                'is_mandatory' => true,
                'is_active' => true,
            ]
        );

        if (count($createdEmployees) >= 3) {
            TrainingAttendee::firstOrCreate(
                ['training_id' => $training1->id, 'employee_id' => $createdEmployees[0]->id],
                [
                    'status' => 'completed',
                    'attended_at' => Carbon::now()->subWeeks(3),
                    'feedback' => 'Exceptional technical depth and highly practical material.',
                ]
            );

            TrainingAttendee::firstOrCreate(
                ['training_id' => $training1->id, 'employee_id' => $createdEmployees[1]->id],
                [
                    'status' => 'completed',
                    'attended_at' => Carbon::now()->subWeeks(3),
                    'feedback' => 'Great insights on container optimization and build caching.',
                ]
            );

            TrainingAttendee::firstOrCreate(
                ['training_id' => $training2->id, 'employee_id' => $createdEmployees[2]->id],
                [
                    'status' => 'registered',
                ]
            );
        }
    }
}

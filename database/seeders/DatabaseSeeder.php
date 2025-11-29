<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\LeaveType;
use App\Models\Leave;
use App\Models\BonusType;
use App\Models\DeductionType;
use App\Models\Payroll;
use App\Models\PayrollBonus;
use App\Models\PayrollDeduction;
use App\Models\TrainingProgram;
use App\Models\TrainingAssignment;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /* -------------------------------------------------
         *  1. DEPARTMENTS
         * ------------------------------------------------- */
        $departments = [];
        foreach (['Marketing', 'IT', 'HR', 'Operations', 'Legal', 'Finance', 'RnD'] as $name) {
            $departments[] = Department::create(['name' => $name]);
        }

        /* -------------------------------------------------
         *  2. DESIGNATIONS
         * ------------------------------------------------- */
        $designations = [];
        foreach ([
            'Junior Developer', 'Senior Developer', 'Manager',
            'Accountant', 'HR Specialist', 'Analyst'
        ] as $title) {
            $designations[] = Designation::create(['title' => $title]);
        }

        /* -------------------------------------------------
         *  3. LEAVE TYPES (Holiday Leave = unpaid)
         * ------------------------------------------------- */
        $leaveTypes = [];
        $leaveData = [
            ['name' => 'Sick Leave',       'is_paid' => true],
            ['name' => 'Vacation Leave',   'is_paid' => true],
            ['name' => 'Unpaid Leave',     'is_paid' => false],
            ['name' => 'Maternity Leave',  'is_paid' => true],
            ['name' => 'Paternity Leave',  'is_paid' => true],
            ['name' => 'Bereavement Leave','is_paid' => true],
            ['name' => 'Personal Leave',   'is_paid' => false],
            ['name' => 'Study Leave',      'is_paid' => false],
            ['name' => 'Sabbatical Leave', 'is_paid' => false],
            ['name' => 'Emergency Leave',  'is_paid' => true],
            ['name' => 'Holiday Leave',    'is_paid' => false],
        ];
        foreach ($leaveData as $d) {
            $leaveTypes[] = LeaveType::create($d);
        }

        /* -------------------------------------------------
         *  4. BONUS TYPES
         * ------------------------------------------------- */
        $bonusTypes = [];
        $bonusData = [
            ['name' => 'Performance Bonus',        'description' => 'For outstanding performance'],
            ['name' => 'Thingyan Bonus',           'description' => 'Water Festival bonus'],
            ['name' => 'Project Completion Bonus', 'description' => 'For completing major projects'],
            ['name' => 'Attendance Bonus',         'description' => 'For perfect attendance'],
            ['name' => 'Referral Bonus',           'description' => 'For successful referrals'],
            ['name' => 'Overtime Bonus',           'description' => 'For extra hours worked'],
            ['name' => 'Team Achievement Bonus',   'description' => 'For team milestones'],
            ['name' => 'Innovation Bonus',         'description' => 'For innovative ideas'],
            ['name' => 'Loyalty Bonus',            'description' => 'For long‑term service'],
            ['name' => 'Safety Bonus',             'description' => 'For maintaining safety standards'],
        ];
        foreach ($bonusData as $d) {
            $bonusTypes[] = BonusType::create($d);
        }

        /* -------------------------------------------------
         *  5. DEDUCTION TYPES
         * ------------------------------------------------- */
        $deductionTypes = [];
        $deductionData = [
            ['name' => 'Income Tax',               'description' => 'Personal income tax'],
            ['name' => 'Late Penalty',             'description' => 'Penalty for late attendance'],
            ['name' => 'Health Insurance',         'description' => 'Employee health insurance'],
            ['name' => 'Loan Repayment',           'description' => 'Repayment of company loan'],
            ['name' => 'Absenteeism Penalty',      'description' => 'Penalty for unexcused absences'],
            ['name' => 'Equipment Damage',         'description' => 'Cost for damaged property'],
            ['name' => 'Pension Contribution',     'description' => 'SSB contribution'],
            ['name' => 'Union Fees',               'description' => 'Union membership fees'],
            ['name' => 'Overpayment Recovery',     'description' => 'Recovery of payroll overpayments'],
            ['name' => 'Travel Advance Recovery',  'description' => 'Recovery of travel advances'],
        ];
        foreach ($deductionData as $d) {
            $deductionTypes[] = DeductionType::create($d);
        }

        /* -------------------------------------------------
         *  6. USERS
         * ------------------------------------------------- */
        $users = [];
        $userData = [
            ['name' => 'Aung Admin',      'email' => 'admin@mycompany.mm',      'type' => 'admin'],
            ['name' => 'Nilar HR',        'email' => 'hr@mycompany.mm',         'type' => 'hr'],
            ['name' => 'Kyaw Employee',   'email' => 'kyaw@mycompany.mm',       'type' => 'employee'],
            ['name' => 'Su Htet',         'email' => 'suhtet@mycompany.mm',     'type' => 'employee'],
            ['name' => 'Phyo Wai',        'email' => 'phyowai@mycompany.mm',    'type' => 'employee'],
            ['name' => 'Htet Manager',    'email' => 'htet.manager@mycompany.mm','type' => 'hr'],
            ['name' => 'Aye Finance',     'email' => 'aye.finance@mycompany.mm','type' => 'hr'],
            ['name' => 'Zaw Support',     'email' => 'zaw.support@mycompany.mm','type' => 'employee'],
            ['name' => 'Min Engineer',    'email' => 'min.engineer@mycompany.mm','type' => 'employee'],
            ['name' => 'Thiri Analyst',   'email' => 'thiri.analyst@mycompany.mm','type' => 'employee'],
        ];
        foreach ($userData as $d) {
            $users[] = User::create([
                'name'      => $d['name'],
                'email'     => $d['email'],
                'password'  => bcrypt('password123'),
                'user_type' => $d['type'],
            ]);
        }

        /* -------------------------------------------------
         *  7. MYANMAR‑REALISTIC EMPLOYEE DATA
         * ------------------------------------------------- */
        $firstNames      = ['Aung','Kyaw','Min','Zaw','Htet','Phyo','Thiha','Wai','Kaung','Chan','Nyi','Lin'];
        $femaleFirstNames= ['Su','Thiri','Nilar','Ei','May','Hnin','Phyu','K江南','Yadanar','Shwe','Moe','Aye'];
        $lastNames       = ['Aung','Kyaw','Min','Zaw','Htet','Phyo','Thiha','Wai','Kaung','Chan','Nyi','Lin','Soe','Naing','Tun','Lwin'];
        $nrcRegions      = ['12/OUKAMA(N)','7/KAMANA(N)','9/BAHANA(N)','14/YAKHANA(N)','1/AHPHANA(N)','4/GAHANA(N)','5/MAHANA(N)','8/TAHANA(N)'];
        $addresses       = [
            'No. 123, Pyay Road, Sanchaung, Yangon',
            'Block 45, 67th Street, Mandalay',
            'Room 3B, Inya Road, Kamayut, Yangon',
            'No. 89, Bogyoke Street, Pabedan, Yangon',
            '78/A, Anawyahtar Road, Bahan, Yangon',
            'No. 12, Thiri Road, North Okkalapa, Yangon',
            '45, Myoma Street, Ahlone, Yangon',
            'Block C, Shwe Gon Daing, Yangon',
            'No. 56, Kyaikkasan Road, Tamwe, Yangon',
            '89, U Wisara Road, Dagon, Yangon',
            'No. 34, 35th Street, Kyauktada, Yangon',
            '12, Thein Phyu Road, Mingalar Taung Nyunt, Yangon',
        ];
        $statuses = ['permanent','contracted','training','intern','inactive'];

        $employees      = [];
        $usedEmails     = [];   // guarantee unique e‑mail
        $usedNrcs       = [];   // guarantee unique NRC
        $emailCounter   = [];   // for same name → suffix

        foreach (range(0, 11) as $i) {
            $isFemale   = $i % 3 === 0;
            $firstName  = $isFemale
                ? $femaleFirstNames[array_rand($femaleFirstNames)]
                : $firstNames[array_rand($firstNames)];
            $lastName   = $lastNames[array_rand($lastNames)];

            // ----- EMAIL (unique) -----
            $baseEmail = strtolower("{$firstName}.{$lastName}@mycompany.mm");
            $emailKey  = $firstName . '.' . $lastName;
            $emailCounter[$emailKey] = ($emailCounter[$emailKey] ?? 0) + 1;
            $email = $emailCounter[$emailKey] === 1
                ? $baseEmail
                : $baseEmail . $emailCounter[$emailKey];

            while (in_array($email, $usedEmails)) {
                $emailCounter[$emailKey]++;
                $email = $baseEmail . $emailCounter[$emailKey];
            }
            $usedEmails[] = $email;

            // ----- NRC (unique) -----
            do {
                $region = $nrcRegions[array_rand($nrcRegions)];
                $num    = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $nrc    = $region . $num;
            } while (in_array($nrc, $usedNrcs));
            $usedNrcs[] = $nrc;

            // ----- OTHER FIELDS -----
            $status       = $i >= 9 ? 'inactive' : $statuses[array_rand($statuses)];
            $basicSalary  = rand(15, 50) * 10000; // 150,000‑500,000 MMK

            $employee = Employee::create([
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'dob'           => Carbon::now()->subYears(rand(22, 50))->format('Y-m-d'),
                'address'       => $addresses[$i],
                'nrc'           => $nrc,
                'phonenumber'   => '09' . rand(2, 9) . str_pad(rand(0, 9999999), 8, '0', STR_PAD_LEFT),
                'email'         => $email,
                'status'        => $status,
                'basic_salary'  => $basicSalary + 0.00,
                'department_id' => $departments[array_rand($departments)]->id,
                'designation_id'=> $designations[array_rand($designations)]->id,
                'user_id'       => ($i < 3) ? $users[$i + 2]->id : null,
            ]);

            $employees[] = $employee;

            if ($i < 3) {
                $users[$i + 2]->update(['employee_id' => $employee->id]);
            }
        }

        /* -------------------------------------------------
         *  8. LEAVES
         * ------------------------------------------------- */
        foreach (range(0, 9) as $i) {
            $emp   = $employees[array_rand($employees)];
            $type  = $leaveTypes[array_rand($leaveTypes)];
            $start = Carbon::now()->startOfMonth()->addDays(rand(0, 15));
            $status= ['pending','approved','declined'][rand(0,2)];

            Leave::create([
                'employee_id'   => $emp->id,
                'leave_type_id' => $type->id,
                'start_date'    => $start,
                'end_date'      => $start->copy()->addDays(rand(1,5)),
                'reason'        => $type->name === 'Holiday Leave'
                                    ? 'Thingyan Festival'
                                    : "Personal reason #{$i}",
                'status'        => $status,
                'approved_by'   => in_array($status, ['approved','declined']) ? $users[1]->id : null,
                'approved_at'   => in_array($status, ['approved','declined']) ? now() : null,
            ]);
        }

        /* -------------------------------------------------
         *  9. PAYROLLS
         * ------------------------------------------------- */
        $payrolls = [];
        $usedEmpMonth = [];
        $months = collect(range(0, 9))
            ->map(fn($i) => Carbon::now()->startOfMonth()->subMonths(6 - $i))
            ->all();

        foreach (range(0, 9) as $i) {
            do {
                $emp   = $employees[array_rand($employees)];
                $month = $months[array_rand($months)];
                $key   = $emp->id . '-' . $month->format('Y-m');
            } while (isset($usedEmpMonth[$key]));
            $usedEmpMonth[$key] = true;

            $worked = rand(20, 30);
            $unpaid = rand(0, 3);
            $bonus  = rand(0, 150000);
            $deduct = rand(0, 50000);
            $daily  = $emp->basic_salary / 30;
            $net    = round(($daily * $worked) + $bonus - $deduct, 2);
            $status = ['pending','approved'][rand(0,1)];

            $payrolls[] = Payroll::create([
                'employee_id'       => $emp->id,
                'month'             => $month,
                'basic_salary'      => $emp->basic_salary,
                'worked_days'       => $worked,
                'unpaid_leave_days' => $unpaid,
                'total_bonus'       => $bonus,
                'total_deduction'   => $deduct,
                'net_salary'        => $net,
                'status'            => $status,
                'approved_by'       => $status === 'approved' ? $users[1]->id : null,
                'approved_at'       => $status === 'approved' ? now() : null,
            ]);
        }

        /* -------------------------------------------------
         * 10. PAYROLL BONUSES & DEDUCTIONS
         * ------------------------------------------------- */
        foreach (range(0, 9) as $i) {
            $p = $payrolls[array_rand($payrolls)];
            PayrollBonus::create([
                'payroll_id'     => $p->id,
                'bonus_type_id'  => $bonusTypes[array_rand($bonusTypes)]->id,
                'amount'         => rand(20000, 100000) + 0.00,
            ]);
        }
        foreach (range(0, 9) as $i) {
            $p = $payrolls[array_rand($payrolls)];
            PayrollDeduction::create([
                'payroll_id'        => $p->id,
                'deduction_type_id' => $deductionTypes[array_rand($deductionTypes)]->id,
                'amount'            => rand(10000, 40000) + 0.00,
            ]);
        }

        /* -------------------------------------------------
         * 11. TRAINING PROGRAMS
         * ------------------------------------------------- */
        $programs = [];
        $progNames = [
            'Laravel & Vue.js Development','Leadership & Team Management','Data Analytics with Python',
            'UI/UX Design Principles','Financial Reporting Standards','Digital Marketing Strategies',
            'HR Compliance & Labor Law','Agile Project Management','Customer Service Excellence',
            'DevOps & Cloud Fundamentals'
        ];
        foreach ($progNames as $n) {
            $programs[] = TrainingProgram::create([
                'name'                    => $n,
                'details'                 => "Comprehensive training on {$n} for skill enhancement.",
                'instructor_employee_id'  => $employees[array_rand($employees)]->id,
                'available_days'          => json_encode(['Mon','Wed','Fri']),
                'available_total_employees'=> rand(8,20),
                'available_time'          => '5:00pm - 7:00pm',
                'status'                  => ['available','active','ended'][rand(0,2)],
            ]);
        }

        /* -------------------------------------------------
         * 12. TRAINING ASSIGNMENTS (unique program‑employee)
         * ------------------------------------------------- */
        $usedPairs = [];
        foreach (range(0, 9) as $i) {
            do {
                $prog = $programs[array_rand($programs)];
                $emp  = $employees[array_rand($employees)];
                $key  = $prog->id . '-' . $emp->id;
            } while (isset($usedPairs[$key]));
            $usedPairs[$key] = true;

            TrainingAssignment::create([
                'training_program_id' => $prog->id,
                'employee_id'         => $emp->id,
                'status'              => ['pending','started','completed'][rand(0,2)],
                'start_date'          => Carbon::now()->startOfMonth()->addDays(rand(0,10)),
                'end_date'            => Carbon::now()->startOfMonth()->addDays(rand(20,30)),
            ]);
        }
    }
}
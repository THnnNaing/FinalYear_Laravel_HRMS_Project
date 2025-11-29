<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GeneratePayrolls extends Command
{
    protected $signature = 'payrolls:generate';
    protected $description = 'Generate payrolls for permanent employees for the previous month';

    public function handle()
    {
        $month = Carbon::now()->startOfMonth()->subMonth();
        $employees = Employee::where('status', 'permanent')->get();

        foreach ($employees as $employee) {
            if (Payroll::where('employee_id', $employee->id)->where('month', $month)->exists()) {
                continue;
            }

            $unpaidLeaveDays = Leave::where('employee_id', $employee->id)
                                    ->where('status', 'approved')
                                    ->whereHas('leaveType', fn($q) => $q->where('is_paid', false))
                                    ->whereBetween('start_date', [$month, $month->copy()->endOfMonth()])
                                    ->sum(DB::raw('DATEDIFF(end_date, start_date) + 1'));

            $workedDays = 30 - $unpaidLeaveDays;
            $dailyRate = $employee->basic_salary / 30;
            $netSalary = $dailyRate * $workedDays;

            Payroll::create([
                'employee_id' => $employee->id,
                'month' => $month,
                'basic_salary' => $employee->basic_salary,
                'worked_days' => $workedDays,
                'unpaid_leave_days' => $unpaidLeaveDays,
                'total_bonus' => 0,
                'total_deduction' => 0,
                'net_salary' => $netSalary,
                'status' => 'pending',
            ]);
        }

        $this->info('Payrolls generated successfully for ' . $month->format('Y-m'));
    }
}
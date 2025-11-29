<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Leave;
use App\Models\PayrollBonus;
use App\Models\PayrollDeduction;
use App\Models\BonusType;
use App\Models\DeductionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    // public function index()
    // {
    //     if (Auth::user()->user_type === 'admin' || Auth::user()->user_type === 'hr') {
    //         $payrolls = Payroll::with(['employee', 'approver'])->get();
    //     } else {
    //         $payrolls = Payroll::where('employee_id', Auth::user()->employee_id)
    //                           ->with(['employee', 'approver'])
    //                           ->get();
    //     }
    //     return view('payrolls.index', compact('payrolls'));
    // }

    public function index()
    {
        // 1. Define the base query with eager loading
        $query = Payroll::with(['employee', 'approver'])
            ->orderBy('month', 'desc');

        // 2. Apply filtering based on user type
        if (Auth::user()->user_type === 'admin' || Auth::user()->user_type === 'hr') {
            // No further query modification needed for HR/Admin
        } else {
            // Employees only see their own payrolls
            $query->where('employee_id', Auth::user()->employee_id);
        }

        // 3. IMPORTANT: Use ->paginate() instead of ->get()
        // This fetches the data AND creates the necessary Paginator object.
        $payrolls = $query->paginate(5); // Adjust '15' to your desired items per page

        return view('payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = Auth::user()->user_type === 'hr' ? Employee::all() : collect([Auth::user()->employee]);
        $bonusTypes = BonusType::all();
        $deductionTypes = DeductionType::all();
        return view('payrolls.create', compact('employees', 'bonusTypes', 'deductionTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|date_format:Y-m',
            'worked_days' => 'nullable|integer|min:0|max:30',
            'bonuses' => 'nullable|array',
            'bonuses.*.bonus_type_id' => 'required_with:bonuses|exists:bonus_types,id',
            'bonuses.*.amount' => 'required_with:bonuses|numeric|min:0',
            'deductions' => 'nullable|array',
            'deductions.*.deduction_type_id' => 'required_with:deductions|exists:deduction_types,id',
            'deductions.*.amount' => 'required_with:deductions|numeric|min:0',
        ]);

        if (Auth::user()->user_type === 'employee' && Auth::user()->employee_id !== $request->employee_id) {
            return redirect()->route('employee.payrolls.index')->with('error', 'Unauthorized action.');
        }

        $employee = Employee::findOrFail($request->employee_id);
        $month = Carbon::createFromFormat('Y-m', $request->month)->startOfMonth();
        $unpaidLeaveDays = Leave::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereHas('leaveType', fn($q) => $q->where('is_paid', false))
            ->whereBetween('start_date', [$month, $month->copy()->endOfMonth()])
            ->sum(DB::raw('DATEDIFF(end_date, start_date) + 1'));

        $workedDays = $employee->status === 'permanent' ? 30 - $unpaidLeaveDays : ($request->worked_days ?? 0);
        $dailyRate = $employee->basic_salary / 30;
        $baseSalary = $dailyRate * $workedDays;
        $totalBonus = collect($request->bonuses)->sum('amount') ?? 0;
        $totalDeduction = collect($request->deductions)->sum('amount') ?? 0;
        $netSalary = $baseSalary + $totalBonus - $totalDeduction;

        $payroll = Payroll::create([
            'employee_id' => $employee->id,
            'month' => $month,
            'basic_salary' => $employee->basic_salary,
            'worked_days' => $workedDays,
            'unpaid_leave_days' => $unpaidLeaveDays,
            'total_bonus' => $totalBonus,
            'total_deduction' => $totalDeduction,
            'net_salary' => $netSalary,
            'status' => 'pending',
        ]);

        if ($request->bonuses) {
            foreach ($request->bonuses as $bonus) {
                PayrollBonus::create([
                    'payroll_id' => $payroll->id,
                    'bonus_type_id' => $bonus['bonus_type_id'],
                    'amount' => $bonus['amount'],
                ]);
            }
        }

        if ($request->deductions) {
            foreach ($request->deductions as $deduction) {
                PayrollDeduction::create([
                    'payroll_id' => $payroll->id,
                    'deduction_type_id' => $deduction['deduction_type_id'],
                    'amount' => $deduction['amount'],
                ]);
            }
        }

        return redirect()->route('hr.payrolls.index')->with('success', 'Payroll created successfully.');
    }

    public function show(Payroll $payroll)
    {
        if (Auth::user()->user_type === 'employee' && Auth::user()->employee_id !== $payroll->employee_id) {
            return redirect()->route('employee.payrolls.index')->with('error', 'Unauthorized action.');
        }
        return view('payrolls.show', compact('payroll'));
    }

    public function approve(Payroll $payroll)
    {
        if (Auth::user()->user_type !== 'hr') {
            return redirect()->route('hr.payrolls.index')->with('error', 'Unauthorized action.');
        }

        $payroll->update([
            'status' => 'approved',
            'approved_by' => Auth::user()->id,
            'approved_at' => now(),
        ]);

        return redirect()->route('hr.payrolls.index')->with('success', 'Payroll approved successfully.');
    }

    public function requestPayroll(Request $request)
    {
        if (Auth::user()->user_type !== 'employee' || Auth::user()->employee->status !== 'permanent') {
            return redirect()->route('employee.payrolls.index')->with('error', 'Unauthorized action.');
        }

        $month = Carbon::now()->startOfMonth()->subMonth();
        $employee = Auth::user()->employee;

        if (Payroll::where('employee_id', $employee->id)->where('month', $month)->exists()) {
            return redirect()->route('employee.payrolls.index')->with('error', 'Payroll already exists for this month.');
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

        return redirect()->route('employee.payrolls.index')->with('success', 'Payroll request submitted successfully.');
    }
}

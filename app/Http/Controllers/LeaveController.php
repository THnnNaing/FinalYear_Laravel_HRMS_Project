<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->user_type === 'hr') {
            $leaves = Leave::with(['employee', 'leaveType'])->latest()->paginate(10);
        } else {
            $leaves = Leave::where('employee_id', $user->employee_id ?? $user->id)
                ->with(['employee', 'leaveType'])
                ->latest()
                ->paginate(10);
        }

        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        // Remove the status filter or check what columns actually exist in your leave_types table
        $leaveTypes = LeaveType::all(); // Get all leave types without filtering

        // If you want to check what columns your table has, you can debug:
        // dd(LeaveType::first()); // This will show you the actual columns

        return view('leaves.create', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validation rules
        $rules = [
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'nullable|string|max:500',
        ];

        // Only HR needs to select an employee
        if ($user->user_type === 'hr') {
            $rules['employee_id'] = 'required|exists:employees,id';
        }

        $request->validate($rules);

        // Determine which employee this leave is for
        $employeeId = $user->user_type === 'hr'
            ? $request->employee_id
            : $user->employee->id;   // ← this line works if you have the relationship

        Leave::create([
            'employee_id'   => $employeeId,
            'leave_type_id' => $request->leave_type_id,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'reason'        => $request->reason ?? null,
            'status'        => $user->user_type === 'hr' ? 'approved' : 'pending',
            'approved_by'   => $user->user_type === 'hr' ? $user->id : null,
            'approved_at'   => $user->user_type === 'hr' ? now() : null,
        ]);

        // Correct redirect for both HR and Employee
        $routeName = $user->user_type === 'hr'
            ? 'hr.leaves.index'
            : 'employee.leaves.index';

        return redirect()
            ->route($routeName)
            ->with('success', 'Leave request submitted successfully!');
    }

    public function show(Leave $leave)
    {
        $user = Auth::user();

        // Authorization check
        if ($user->user_type !== 'hr' && $leave->employee_id !== ($user->employee_id ?? $user->id)) {
            abort(403, 'Unauthorized action.');
        }

        $leave->load(['employee', 'leaveType', 'approver']);

        return view('leaves.show', compact('leave'));
    }

    public function approve(Leave $leave)
    {
        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('hr.leaves.index')->with('success', 'Leave request approved successfully.');
    }

    public function decline(Leave $leave)
    {
        $leave->update([
            'status' => 'declined',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('hr.leaves.index')->with('success', 'Leave request declined successfully.');
    }

    public function searchEmployees(Request $request)
    {
        // The query parameter name used by Select2 is 'q'
        $search = $request->get('q');

        $employees = Employee::when($search, function ($query, $search) {
            // Only apply the search filter if a term is provided
            $query->where(function ($subquery) use ($search) {
                $subquery->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('employee_id', 'like', '%' . $search . '%');
            });
        })
            ->select('id', 'first_name', 'last_name')
            ->limit(10)
            ->get();

        // CRITICAL: Format data for Select2: [{id: 1, text: 'Name'}]
        $results = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'text' => $employee->first_name . ' ' . $employee->last_name .
                    ' (ID: ' . $employee->employee_id . ')', // Added employee ID for clarity
            ];
        });

        // The entire response must be encapsulated in a 'results' object for Select2
        return response()->json(['results' => $results]);
    }
}

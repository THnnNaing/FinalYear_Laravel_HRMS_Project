<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // app/Http/Controllers/EmployeeController.php
    public function index()
    {
        $employees = Employee::with(['department', 'designation'])->paginate(5);
        return view('employees.index', compact('employees'));
    }

    // 👇 UPDATED: Added Request $request as the first parameter
    public function resetPassword(Request $request, Employee $employee)
    {
        if (!$employee->user_id) {
            $redirectRoute = Auth::user()->user_type === 'hr' ? 'hr.employees.index' : 'employees.index';
            return redirect()->route($redirectRoute)->with('error', 'No user account linked to this employee.');
        }

        $user = User::find($employee->user_id);
        if (!$user) {
            $redirectRoute = Auth::user()->user_type === 'hr' ? 'hr.employees.index' : 'employees.index';
            return redirect()->route($redirectRoute)->with('error', 'User account not found.');
        }

        $user->update(['password' => Hash::make('password')]);

        $redirectRoute = Auth::user()->user_type === 'hr' ? 'hr.employees.index' : 'employees.index';
        return redirect()->route($redirectRoute)->with('success', 'Password reset to default for ' . $employee->first_name . ' ' . $employee->last_name . '.');
    }

    public function create()
    {
        $departments = Department::all();
        $designations = Designation::all();
        return view('employees.create', compact('departments', 'designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'address' => 'required|string',
            'nrc' => ['required', 'string', 'regex:/^\d{1,2}\/[A-Z]{3}\d{6}$/', 'unique:employees,nrc'],
            'phonenumber' => 'required|string|max:20|unique:employees,phonenumber',
            'email' => 'required|email|unique:employees,email',
            'status' => ['required', Rule::in(['permanent', 'contracted', 'training', 'intern'])],
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'basic_salary' => 'required|numeric|min:0',
        ]);

        Employee::create($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        if (Auth::user()->user_type === 'employee' && Auth::user()->employee_id !== $employee->id) {
            return redirect()->route('employee.dashboard')->with('error', 'Unauthorized action.');
        }

        $departments = Department::all();
        $designations = Designation::all();
        return view('employees.edit', compact('employee', 'departments', 'designations'));
    }

    public function update(Request $request, Employee $employee)
    {
        if (Auth::user()->user_type === 'employee' && Auth::user()->employee_id !== $employee->id) {
            return redirect()->route('employee.dashboard')->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'address' => 'required|string',
            // 'nrc' => ['required', 'string', 'regex:/^\d{1,2}\/[A-Z]{3}\d{6}$/', Rule::unique('employees')->ignore($employee->id)],
            'phonenumber' => ['required', 'string', 'max:20', Rule::unique('employees')->ignore($employee->id)],
            'email' => ['required', 'email', Rule::unique('employees')->ignore($employee->id)],
            'status' => ['required', Rule::in(['permanent', 'contracted', 'training', 'intern'])],
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'basic_salary' => 'required|numeric|min:0',
        ]);

        $employee->update($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

public function destroy(Employee $employee)
{
    try {
        // 1. Delete all related training programs (Child Records)
        $employee->trainingPrograms()->delete(); // This handles the cascade/deletion

        // 2. Then, delete the employee (Parent Record)
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');

    } catch (\Illuminate\Database\QueryException $e) {
        // Optional: Catch a more general QueryException if needed,
        // but handling the relationship first is the cleanest approach.
        return back()->with('error', 'Cannot delete employee. Some related data could not be processed.');
    }
}
    public function show(Employee $employee)
    {
        if (Auth::user()->user_type === 'employee' && Auth::user()->employee_id !== $employee->id) {
            return redirect()->route('employee.dashboard')->with('error', 'Unauthorized action.');
        }

        return view('employees.show', compact('employee'));
    }

    public function dashboard()
    {
        $employee = Auth::user()->employee;
        return view('employee.dashboard', compact('employee'));
    }

    public function searchEmployees(Request $request)
    {
        $search = $request->get('q');

        $employees = Employee::where('status', 'permanent')
            ->when($search, function ($query, $search) {
                $query->where(function ($subquery) use ($search) {
                    $subquery->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%')
                        ->orWhere('employee_id', 'like', '%' . $search . '%');
                });
            })
            ->select('id', 'first_name', 'last_name', 'employee_id')
            ->limit(10)
            ->get();

        $results = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'text' => $employee->first_name . ' ' . $employee->last_name .
                    ' (ID: ' . $employee->employee_id . ')',
            ];
        });

        return response()->json(['results' => $results]);
    }

   
}

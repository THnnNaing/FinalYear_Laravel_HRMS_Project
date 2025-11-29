<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all user accounts linked to employees
     */
    public function index()
    {
        $users = User::with('employee.department', 'employee.designation')
                     ->whereNotNull('employee_id')
                     ->orderBy('name')
                     ->paginate(15);

        return view('useraccounts.index', compact('users'));
    }

    /**
     * Show form to create a new user account
     * Only shows employees who do NOT have a user account yet
     */
    public function create()
    {
        $employeesWithoutAccount = Employee::whereNull('user_id')
            ->orWhere('user_id', 0)
            ->with('department', 'designation')
            ->orderBy('first_name')
            ->get();

        return view('useraccounts.create', compact('employeesWithoutAccount'));
    }

    /**
     * Store a newly created user account
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'user_type'   => 'required|in:admin,hr,employee',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        // Prevent creating duplicate account
        if ($employee->user_id || $employee->user()->exists()) {
            return back()->with('error', 'This employee already has a user account.');
        }

        // Create the user account
        $user = User::create([
            'name'        => $employee->first_name . ' ' . $employee->last_name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'user_type'   => $request->user_type,
            'employee_id' => $employee->id,
        ]);

        // Link back in employees table
        $employee->update(['user_id' => $user->id]);

        return redirect()->route('admin.useraccounts.index')
            ->with('success', "User account created successfully for {$employee->first_name} {$employee->last_name}");
    }

    /**
     * Show the form for editing the specified user account
     */
    public function edit(User $user)
    {
        if (!$user->employee_id || !$user->employee) {
            return redirect()->route('admin.useraccounts.index')
                ->with('error', 'This user is not linked to any employee.');
        }

        $employee = $user->employee;

        return view('useraccounts.edit', compact('user', 'employee'));
    }

    /**
     * Update the specified user account
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password'  => 'nullable|string|min:8|confirmed',
            'user_type' => 'required|in:admin,hr,employee',
        ]);

        $data = [
            'email'     => $request->email,
            'user_type' => $request->user_type,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.useraccounts.index')
            ->with('success', 'User account updated successfully.');
    }

    /**
     * Remove the user account and unlink from employee
     */
    public function destroy(User $user)
    {
        $name = $user->name;

        // Unlink from employee first
        if ($user->employee) {
            $user->employee->update(['user_id' => null]);
        }

        $user->delete();

        return back()->with('success', "User account deleted: {$name}");
    }

    /**
     * Reset password to default ("password")
     */
    public function resetPassword(User $user)
    {
        $user->update(['password' => Hash::make('password')]);

        return back()->with('success', "Password reset to 'password' for {$user->name}");
    }
}
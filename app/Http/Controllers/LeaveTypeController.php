<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveTypeController extends Controller
{
    public function index()
    {
        // Fetch leave types and paginate them (e.g., 10 per page)
        $leaveTypes = LeaveType::paginate(5);

        // Pass the paginated results to the view
        return view('leave_types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('leave_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types',
            'is_paid' => 'required|boolean',
        ]);

        LeaveType::create($request->only(['name', 'is_paid']));

        return redirect()->route('leave_types.index')->with('success', 'Leave type created successfully.');
    }

    public function edit(LeaveType $leaveType)
    {
        return view('leave_types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $leaveType->id,
            'is_paid' => 'required|boolean',
        ]);

        $leaveType->update($request->only(['name', 'is_paid']));

        return redirect()->route('leave_types.index')->with('success', 'Leave type updated successfully.');
    }

    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();
        return redirect()->route('leave_types.index')->with('success', 'Leave type deleted successfully.');
    }
}

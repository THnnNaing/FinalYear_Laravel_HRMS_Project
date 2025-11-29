<?php

namespace App\Http\Controllers;

use App\Models\TrainingProgram;
use App\Models\Employee;
use Illuminate\Http\Request;

class TrainingProgramController extends Controller
{
    public function index()
    {
        $programs = TrainingProgram::with('instructor')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('training_programs.index', compact('programs'));
    }

    public function create()
    {
        return view('training_programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                      => 'required|string|max:255',
            'details'                   => 'nullable|string',
            'instructor_employee_id'    => 'required|exists:employees,id',
            'available_days'            => 'required|array|min:1',
            'available_days.*'          => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'available_time'            => 'required|string|max:255',
            'available_total_employees' => 'required|integer|min:1',
            'status'                    => 'required|in:available,active,ended',
        ]);

        TrainingProgram::create([
            'name'                      => $request->name,
            'details'                   => $request->details,
            'instructor_employee_id'    => $request->instructor_employee_id,
            'available_days'            => $request->available_days, // FIXED: Now passing the array (will be cast to JSON by the model)
            'available_time'            => $request->available_time,
            'available_total_employees' => $request->available_total_employees,
            'status'                    => $request->status,
        ]);

        return redirect()->route('training_programs.index')
            ->with('success', 'Training program created successfully.');
    }

    public function show(TrainingProgram $trainingProgram)
    {
        $trainingProgram->load('instructor');
        return view('training_programs.show', compact('trainingProgram'));
    }

    public function edit(TrainingProgram $trainingProgram)
    {
        return view('training_programs.edit', compact('trainingProgram'));
    }

    public function update(Request $request, TrainingProgram $trainingProgram)
    {
        $request->validate([
            'name'                      => 'required|string|max:255',
            'details'                   => 'nullable|string',
            'instructor_employee_id'    => 'required|exists:employees,id',
            'available_days'            => 'required|array|min:1',
            'available_days.*'          => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'available_time'            => 'required|string|max:255',
            'available_total_employees' => 'required|integer|min:1',
            'status'                    => 'required|in:available,active,ended',
        ]);

        $trainingProgram->update([
            'name'                      => $request->name,
            'details'                   => $request->details,
            'instructor_employee_id'    => $request->instructor_employee_id,
            'available_days'            => $request->available_days, // FIXED: Now passing the array (will be cast to JSON by the model)
            'available_time'            => $request->available_time,
            'available_total_employees' => $request->available_total_employees,
            'status'                    => $request->status,
        ]);

        return redirect()->route('training_programs.index')
            ->with('success', 'Training program updated successfully.');
    }

    public function destroy(TrainingProgram $trainingProgram)
    {
        $trainingProgram->delete();
        return redirect()->route('training_programs.index')
            ->with('success', 'Training program deleted successfully.');
    }

    /**
     * AJAX Search for Instructor (Select2)
     * NOTE: Temporarily searching/displaying against the primary key 'id' 
     * instead of 'employee_id' to prevent query failure if that column is missing.
     */
    public function searchEmployees(Request $request)
    {
        $search = $request->get('q');

        $employees = Employee::where('status', 'permanent')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                      ->orWhere('id', 'like', "%{$search}%"); // CHANGED: Using 'id' (primary key)
                });
            })
            // CHANGED: Only selecting existing columns
            ->select('id', 'first_name', 'last_name') 
            ->limit(15)
            ->get();

        $results = $employees->map(function ($employee) {
            return [
                'id'   => $employee->id,
                // CHANGED: Displaying primary key 'id'
                'text' => "{$employee->first_name} {$employee->last_name} (ID: {$employee->id})"
            ];
        });

        return response()->json(['results' => $results]);
    }
}
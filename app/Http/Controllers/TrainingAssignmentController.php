<?php

namespace App\Http\Controllers;

use App\Models\TrainingProgram;
use App\Models\TrainingAssignment;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingAssignmentController extends Controller
{
    public function index()
    {
        if (Auth::user()->user_type === 'hr') {
            $assignments = TrainingAssignment::with(['trainingProgram', 'employee'])->get();
        } else {
            $assignments = TrainingAssignment::where('employee_id', Auth::user()->employee_id)
                ->with(['trainingProgram', 'employee'])
                ->get();
        }
        return view('training_assignments.index', compact('assignments'));
    }

    public function create()
    {
        $programs = TrainingProgram::whereIn('status', ['available', 'active'])->get();
        $employees = Employee::all();
        return view('training_assignments.create', compact('programs', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'training_program_id' => 'required|exists:training_programs,id',
            'employee_id' => 'required|exists:employees,id',
            'status' => 'required|in:pending,started,completed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $program = TrainingProgram::findOrFail($request->training_program_id);

        // Prevent instructor from being assigned as a trainee
        if ($request->employee_id == $program->instructor_employee_id) {
            return redirect()->route('hr.training_assignments.create')
                ->withErrors(['employee_id' => 'The selected employee is the instructor for this program.']);
        }

        // Check if total assignments exceed available_total_employees
        $currentAssignments = TrainingAssignment::where('training_program_id', $program->id)->count();
        if ($currentAssignments >= $program->available_total_employees) {
            return redirect()->route('hr.training_assignments.create')
                ->withErrors(['training_program_id' => 'This program has reached its maximum number of participants.']);
        }

        TrainingAssignment::create($request->all());

        return redirect()->route('hr.training_assignments.index')->with('success', 'Employee assigned to training program successfully.');
    }

    public function show(TrainingAssignment $trainingAssignment)
    {
        if (Auth::user()->user_type === 'employee' && Auth::user()->employee_id !== $trainingAssignment->employee_id) {
            return redirect()->route('employee.training_assignments.index')->with('error', 'Unauthorized action.');
        }

        // Load co-trainees for the same program
        $coTrainees = TrainingAssignment::where('training_program_id', $trainingAssignment->training_program_id)
            ->where('employee_id', '!=', $trainingAssignment->employee_id)
            ->with('employee')
            ->get();

        return view('training_assignments.show', compact('trainingAssignment', 'coTrainees'));
    }

    public function update(Request $request, TrainingAssignment $trainingAssignment)
    {
        $request->validate([
            'status' => 'required|in:pending,started,completed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $trainingAssignment->update($request->only(['status', 'start_date', 'end_date']));

        return redirect()->route('hr.training_assignments.index')->with('success', 'Training assignment updated successfully.');
    }

    public function edit(TrainingAssignment $trainingAssignment)
    {
        // Only HR can edit assignments
        if (Auth::user()->user_type !== 'hr') {
            abort(403);
            abort(403, 'Unauthorized action.');
        }

        $programs = TrainingProgram::whereIn('status', ['available', 'active'])->get();
        $employees = Employee::all();

        return view('training_assignments.edit', compact('trainingAssignment', 'programs', 'employees'));
    }
}

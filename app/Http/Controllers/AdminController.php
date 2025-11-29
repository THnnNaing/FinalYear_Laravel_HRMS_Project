<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\TrainingProgram;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // -------------------------------------------------
        // 1. Top Stats
        // -------------------------------------------------
        $totalEmployees = Employee::count();
        $totalUsers     = User::count();

        $totalTrainings = class_exists('App\Models\TrainingProgram')
            ? TrainingProgram::count()
            : 0;

        // -------------------------------------------------
        // 2. Department Distribution (Pie/Doughnut)
        // -------------------------------------------------
        $departmentData = Department::withCount('employees')
            ->get()
            ->map(fn($d) => ['label' => $d->name ?? 'Unnamed', 'count' => $d->employees_count]);

        $departmentLabels = $departmentData->pluck('label')
            ->filter()                 // <-- remove null / empty strings
            ->values()
            ->toArray();

        $departmentCounts = $departmentData->pluck('count')
            ->values()
            ->toArray();

        // -------------------------------------------------
        // 3. Designation Distribution (Pie/Doughnut)  <-- THE FIX
        // -------------------------------------------------
        $designationData = Designation::withCount('employees')
            ->get()
            ->map(fn($d) => ['label' => $d->title ?? 'Unnamed', 'count' => $d->employees_count]);

        $designationLabels = $designationData->pluck('label')
            ->filter()                 // <-- removes null / empty
            ->values()
            ->toArray();

        $designationCounts = $designationData->pluck('count')
            ->values()
            ->toArray();

        // -------------------------------------------------
        // 4. Monthly Hires (Bar chart)
        // -------------------------------------------------
        $hiresByMonth = Employee::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $monthlyHires = array_fill(1, 12, 0);
        foreach ($hiresByMonth as $month => $count) {
            $monthlyHires[$month] = (int) $count;
        }

        $monthlyLabels = ['Jan','Feb','Mar','Apr','May','Jun',
                          'Jul','Aug','Sep','Oct','Nov','Dec'];

        // -------------------------------------------------
        // 5. Return view with clean data
        // -------------------------------------------------
        return view('admin.dashboard', [
            // Top stats
            'totalEmployees'   => $totalEmployees,
            'totalUsers'       => $totalUsers,
            'totalTrainings'   => $totalTrainings,

            // Department chart
            'departmentLabels' => $departmentLabels,
            'departmentCounts' => $departmentCounts,

            // Designation chart (now safe)
            'designationLabels' => $designationLabels,
            'designationCounts' => $designationCounts,

            // Hires chart
            'monthlyLabels' => $monthlyLabels,
            'monthlyHires'  => array_values($monthlyHires),
        ]);
    }
}
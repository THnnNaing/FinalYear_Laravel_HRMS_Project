<?php

namespace App\Http\Controllers;

use App\Models\DeductionType;
use Illuminate\Http\Request;

class DeductionTypeController extends Controller
{
    public function index()
    {
        // Fetch deduction types and paginate them (e.g., 10 per page)
        $deductionTypes = DeductionType::paginate(5);

        // Pass the paginated results to the view
        return view('deduction_types.index', compact('deductionTypes'));
    }

    public function create()
    {
        return view('deduction_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:deduction_types',
            'description' => 'nullable|string',
        ]);

        DeductionType::create($request->only(['name', 'description']));

        return redirect()->route('deduction_types.index')->with('success', 'Deduction type created successfully.');
    }

    public function edit(DeductionType $deductionType)
    {
        return view('deduction_types.edit', compact('deductionType'));
    }

    public function update(Request $request, DeductionType $deductionType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:deduction_types,name,' . $deductionType->id,
            'description' => 'nullable|string',
        ]);

        $deductionType->update($request->only(['name', 'description']));

        return redirect()->route('deduction_types.index')->with('success', 'Deduction type updated successfully.');
    }

    public function destroy(DeductionType $deductionType)
    {
        $deductionType->delete();
        return redirect()->route('deduction_types.index')->with('success', 'Deduction type deleted successfully.');
    }
}

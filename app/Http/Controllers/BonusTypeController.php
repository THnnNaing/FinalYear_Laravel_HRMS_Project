<?php

namespace App\Http\Controllers;

use App\Models\BonusType;
use Illuminate\Http\Request;

class BonusTypeController extends Controller
{
    public function index()
    {
        // Fetch bonus types and paginate them (e.g., 10 per page)
        $bonusTypes = BonusType::paginate(5);

        // Pass the paginated results to the view
        return view('bonus_types.index', compact('bonusTypes'));
    }

    public function create()
    {
        return view('bonus_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:bonus_types',
            'description' => 'nullable|string',
        ]);

        BonusType::create($request->only(['name', 'description']));

        return redirect()->route('bonus_types.index')->with('success', 'Bonus type created successfully.');
    }

    public function edit(BonusType $bonusType)
    {
        return view('bonus_types.edit', compact('bonusType'));
    }

    public function update(Request $request, BonusType $bonusType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:bonus_types,name,' . $bonusType->id,
            'description' => 'nullable|string',
        ]);

        $bonusType->update($request->only(['name', 'description']));

        return redirect()->route('bonus_types.index')->with('success', 'Bonus type updated successfully.');
    }

    public function destroy(BonusType $bonusType)
    {
        $bonusType->delete();
        return redirect()->route('bonus_types.index')->with('success', 'Bonus type deleted successfully.');
    }
}

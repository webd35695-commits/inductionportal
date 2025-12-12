<?php

namespace App\Http\Controllers;

use App\Models\InductionPhase;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Http\Requests\StoreInductionPhaseRequest;
use App\Http\Requests\UpdateInductionPhaseRequest;

class InductionPhaseController extends Controller
{
    public function index()
    {
        $inductionPhases = InductionPhase::orderBy('created_at', 'desc')->get();

        return Inertia::render('Dashboard/Admin/InductionPhase/Index', [
            'inductionPhases' => $inductionPhases,
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Admin/InductionPhase/Create');
    }

    public function store(StoreInductionPhaseRequest $request)
    {
        InductionPhase::create($request->validated());

        return redirect()->route('InductionPhase.index')
            ->with('message', 'Induction phase created successfully.');
    }

    public function show(InductionPhase $inductionPhase)
    {
        return Inertia::render('Dashboard/Admin/InductionPhase/Show', [
            'inductionPhase' => $inductionPhase,
        ]);
    }

    public function edit($id)
    {
        $inductionPhase = InductionPhase::findOrFail($id);
        return Inertia::render('Dashboard/Admin/InductionPhase/Edit', [
            'inductionPhase' => $inductionPhase,
        ]);
    }

    public function update(Request $request, InductionPhase $inductionPhase)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $inductionPhase->update($request->all());
        return redirect()->route('InductionPhase.index')->with('message', 'Induction phase updated successfully');
    }

    public function destroy($id)
    {
        $inductionPhase = InductionPhase::findOrFail($id);
        $inductionPhase->delete();

        return redirect()->route('InductionPhase.index')
            ->with('message', 'Induction phase deleted successfully.');
    }
}

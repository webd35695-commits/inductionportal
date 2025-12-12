<?php

namespace App\Http\Controllers;

use App\Models\QualificationType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QualificationTypeController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Admin/QualificationTypes/Index', [
            'qualificationTypes' => QualificationType::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Admin/QualificationTypes/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:qualification_types,name',
            'status' => 'required|in:Active,Inactive',
        ]);

        QualificationType::create($request->all());
        return redirect()->route('qualification-types.index')->with('message', 'Qualification type added successfully.');
    }

    public function show(QualificationType $qualificationType)
    {
        return Inertia::render('Dashboard/Admin/QualificationTypes/Show', [
            'qualificationType' => $qualificationType,
        ]);
    }

    public function edit(QualificationType $qualificationType)
    {
        return Inertia::render('Dashboard/Admin/QualificationTypes/Edit', [
            'qualificationType' => $qualificationType,
        ]);
    }

    public function update(Request $request, QualificationType $qualificationType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:qualification_types,name,'.$qualificationType->id,
            'status' => 'required|in:Active,Inactive',
        ]);

        $qualificationType->update($request->all());
        return redirect()->route('qualification-types.index')->with('message', 'Qualification type updated successfully.');
    }

    public function destroy(QualificationType $qualificationType)
    {
        $qualificationType->delete();
        return back()->with('message', 'Qualification type deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\QualificationCategory;
use App\Models\QualificationType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QualificationCategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Admin/QualificationCategories/Index', [
            'qualificationCategories' => QualificationCategory::with('qualificationType')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Admin/QualificationCategories/Create', [
            'qualificationTypes' => QualificationType::where('status', 'Active')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'qualification_type_id' => 'required|exists:qualification_types,id',
            'name' => 'required|string|max:255|unique:qualification_categories,name',
            'status' => 'required|in:Active,Inactive',
        ]);

        QualificationCategory::create($request->all());
        return redirect()->route('qualification-categories.index')->with('message', 'Qualification category added successfully.');
    }

    public function show(QualificationCategory $qualificationCategory)
    {
        return Inertia::render('Dashboard/Admin/QualificationCategories/Show', [
            'qualificationCategory' => $qualificationCategory->load('qualificationType'),
        ]);
    }

    public function edit(QualificationCategory $qualificationCategory)
    {
        return Inertia::render('Dashboard/Admin/QualificationCategories/Edit', [
            'qualificationCategory' => $qualificationCategory,
            'qualificationTypes' => QualificationType::where('status', 'Active')->get(),
        ]);
    }

    public function update(Request $request, QualificationCategory $qualificationCategory)
    {
        $request->validate([
            'qualification_type_id' => 'required|exists:qualification_types,id',
            'name' => 'required|string|max:255|unique:qualification_categories,name,'.$qualificationCategory->id,
            'status' => 'required|in:Active,Inactive',
        ]);

        $qualificationCategory->update($request->all());
        return redirect()->route('qualification-categories.index')->with('message', 'Qualification category updated successfully.');
    }

    public function destroy(QualificationCategory $qualificationCategory)
    {
        $qualificationCategory->delete();
        return back()->with('message', 'Qualification category deleted successfully.');
    }
}

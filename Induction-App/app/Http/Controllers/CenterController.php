<?php

namespace App\Http\Controllers;

use App\Models\center;
use App\Models\cities;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CenterController extends Controller
{
    public function index()
    {

        return Inertia::render('Dashboard/Admin/Center/Index', [
            'Center' => center::with('cities')->get(),
        ]);
    }

    public function create()
    {

        return Inertia::render('Dashboard/Admin/Center/Create', [
            'citiess' => cities::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
            'name' => 'required|string|max:255|unique:centers,name',
            'status' => 'required|in:Active,Inactive',
        ]);

        center::create($request->all());
        return redirect()->route('Centers.index')->with('message', 'Center added successfully.');
    }

    public function show(center $center)
    {
        return Inertia::render('Dashboard/Admin/Center/Show', [
            'center' => $center->load('cities'),
        ]);
    }

    public function edit(center $center)
    {
        return Inertia::render('Dashboard/Admin/Center/Edit', [
            'center' => $center,
            'citiess' => cities::get(),
        ]);
    }

    public function update(Request $request, center $center)
    {
        $request->validate([
            'qualification_type_id' => 'required|exists:qualification_types,id',
            'name' => 'required|string|max:255|unique:qualification_categories,name,'.$center->id,
            'status' => 'required|in:Active,Inactive',
        ]);

        $center->update($request->all());
        return redirect()->route('Centers.index')->with('message', 'Center updated successfully.');
    }

    public function destroy(center $center)
    {

        $center->delete();
        return back()->with('message', 'Center deleted successfully.');
    }
}

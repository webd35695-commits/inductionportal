<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function Laravel\Prompts\select;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   

public function index()
{
    $districts = District::with('province')->orderBy('name', 'asc')->get();
    $provinces = Province::orderBy('name', 'asc')->get();

    return Inertia::render('Dashboard/Admin/Districts/index', [
        'districts' => $districts,
        'provinces' => $provinces,
        'flash' => [
            'message' => session('message'),
            'error' => session('error'),
        ],
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{

    $validated = $request->validate([
        'province_id' => 'required|exists:provinces,id',
        'name' => 'required|string|max:255',
        'status' => 'required|in:Active,Inactive',
    ]);

    District::create($validated);

    return redirect()->route('districts.index')->with('message', 'District created successfully');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, District $district)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:districts,name,' . $district->id],
            'province_id' => ['required', 'exists:provinces,id'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        // Update the district
        $district->update($validated);

        // Flash success message
        return redirect()->route('districts.index')->with('flash.message', 'District updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $district = District::findOrFail($id);
        $district->delete();
        return redirect()->route('districts.index')->with('message', 'District deleted successfully');
    }

    public function fetch(Request $request)
    {
        dd($request->all());
        $provinceId = $request->input('province_id');
        $districts = District::where('province_id', $provinceId)->orderBy('name', 'asc')->get();

        return response()->json($districts);
    }
}

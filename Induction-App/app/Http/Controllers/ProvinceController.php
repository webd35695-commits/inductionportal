<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;
use Inertia\Inertia;

class ProvinceController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Admin/Provinces/Index', [
            'provinces' => Province::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Admin/Provinces/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $existingProvince = Province::where('name', $request->name)->first();

        if ($existingProvince) {
            return back()
                ->withErrors(['duplicate' => 'This province already exists in the Application.'])
                ->withInput();
        }

        Province::create($request->all());
        return redirect()->route('provinces.index')->with('message', 'Province added successfully.');
    }

    public function show(Province $province)
    {
        return Inertia::render('Dashboard/Admin/Provinces/Show', ['province' => $province]);
    }

    public function edit(Province $province)
    {
        return Inertia::render('Dashboard/Admin/Provinces/Edit', ['province' => $province]);
    }

    public function update(Request $request, Province $province)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $province->update($request->all());
        return redirect()->route('provinces.index')->with('message', 'Province updated.');
    }

    public function destroy(Province $province)
    {

        //  Provnice ka data ,
        // dd($province);
        $province->delete();
        return back()->with('message', 'Province deleted successfully.');
    }
}

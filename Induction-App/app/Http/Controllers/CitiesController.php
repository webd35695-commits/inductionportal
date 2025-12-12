<?php
// app/Http/Controllers/CityController.php
namespace App\Http\Controllers;

use App\Models\cities;
use App\Models\district;
use Inertia\Inertia;
use App\Http\Requests\StorecitiesRequest;
use App\Http\Requests\UpdatecitiesRequest;
use Illuminate\Support\Facades\Redirect;

class CitiesController extends Controller
{
    public function index()
    {


        return Inertia::render('Dashboard/Admin/Cities/index', [
            'cities' => cities::with('district')
                ->orderBy('name')
                ->paginate(10)
                ->through(fn($city) => [
                    'id' => $city->id,
                    'name' => $city->name,
                    'district' => $city->district->name,
                ]),
            'can' => [
                'create' => auth()->user()->can('create', cities::class),
            ]
        ]);
    }

    public function create()
    {
        // $this->authorize('create', cities::class);

        return Inertia::render('Dashboard/Admin/Cities/Create', [
            'districts' => district::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StorecitiesRequest $request)
    {
        $city = cities::create($request->validated());

        return Redirect::route('cities.index')
            ->with('message', 'City created successfully.');
    }

    public function edit(cities $city)
    {
        // $this->authorize('update', $city);

        return Inertia::render('Dashboard/Admin/Cities/Edit', [
            'city' => [
                'id' => $city->id,
                'name' => $city->name,
                'district_id' => $city->district_id,
            ],
            'districts' => district::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdatecitiesRequest $request, cities $city)
    {

        $city->update($request->validated());

        return Redirect::route('cities.index')
            ->with('message', 'City updated successfully.');
    }

    public function destroy(cities $city)
    {
        // $this->authorize('delete', $city);

        $city->delete();

        return Redirect::route('cities.index')
            ->with('message', 'City deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Admin\Fees_allotment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFees_allotmentRequest;
use App\Http\Requests\UpdateFees_allotmentRequest;

class FeesAllotmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreFees_allotmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Fees_allotment $fees_allotment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fees_allotment $fees_allotment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFees_allotmentRequest $request, Fees_allotment $fees_allotment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fees_allotment $fees_allotment)
    {
        //
    }
}

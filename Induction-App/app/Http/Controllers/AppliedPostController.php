<?php

namespace App\Http\Controllers;

use App\Models\applied_post;
use App\Http\Controllers\Controller;
use App\Http\Requests\Storeapplied_postRequest;
use App\Http\Requests\Updateapplied_postRequest;

class AppliedPostController extends Controller
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
    public function store(Storeapplied_postRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(applied_post $applied_post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(applied_post $applied_post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateapplied_postRequest $request, applied_post $applied_post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(applied_post $applied_post)
    {
        //
    }
}

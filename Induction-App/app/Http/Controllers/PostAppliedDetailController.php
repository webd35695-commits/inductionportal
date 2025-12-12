<?php

namespace App\Http\Controllers;

use App\Models\post_applied_detail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Storepost_applied_detailRequest;
use App\Http\Requests\Updatepost_applied_detailRequest;

class PostAppliedDetailController extends Controller
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
    public function store(Storepost_applied_detailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(post_applied_detail $post_applied_detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(post_applied_detail $post_applied_detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatepost_applied_detailRequest $request, post_applied_detail $post_applied_detail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(post_applied_detail $post_applied_detail)
    {
        //
    }
}

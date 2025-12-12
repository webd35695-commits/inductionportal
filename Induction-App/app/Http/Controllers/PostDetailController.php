<?php

namespace App\Http\Controllers;

use App\Models\Admin\post_detail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Storepost_detailRequest;
use App\Http\Requests\Updatepost_detailRequest;

class PostDetailController extends Controller
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
    public function store(Storepost_detailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(post_detail $post_detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(post_detail $post_detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatepost_detailRequest $request, post_detail $post_detail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(post_detail $post_detail)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\user_contact;
use App\Http\Controllers\Controller;
use App\Http\Requests\Storeuser_contactRequest;
use App\Http\Requests\Updateuser_contactRequest;

class UserContactController extends Controller
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
    public function store(Storeuser_contactRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(user_contact $user_contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(user_contact $user_contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateuser_contactRequest $request, user_contact $user_contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user_contact $user_contact)
    {
        //
    }
}

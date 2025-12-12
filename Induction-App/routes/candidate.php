<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::prefix('candidate')->middleware(['auth', 'verified', 'role:candidate'])->group(function () {
    Route::get('/dashboard', function () {
        return inertia('Dashboard/Candidate');
    })->name('candidate.dashboard');
});

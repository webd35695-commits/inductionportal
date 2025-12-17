<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::prefix('candidate')->middleware(['auth', 'verified', 'role:candidate'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\CandidateDashboardController::class, 'index'])->name('candidate.dashboard');
});

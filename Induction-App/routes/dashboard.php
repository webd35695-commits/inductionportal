<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->hasRole('induction')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('candidate')) {
        return redirect()->route('candidate.dashboard');
    }
    return inertia('Dashboard/Index');
})->middleware(['auth', 'verified'])->name('dashboard');

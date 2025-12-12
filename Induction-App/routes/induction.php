<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;




Route::prefix('induction')->middleware(['auth', 'verified', 'role:induction'])->group(function () {
    Route::get('/dashboard', function () {
        return inertia('Dashboard/Induction/Index', [
            'auth' => [
                'user' => auth()->user()->only(['id', 'name', 'email']),
                'roles' => auth()->user()->getRoleNames(),
                'can' => auth()->user()->getAllPermissions()->pluck('name')
            ]
        ]);
    })->name('induction.dashboard');
});




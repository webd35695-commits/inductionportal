<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Role Based Admin Route...
Route::prefix('admin')->middleware(['auth', 'verified', 'role:super_admin|admin'])->group(function () {
    Route::get('/dashboard', function () {
      return inertia('Dashboard/Admin/Index', [
            'auth' => [
                'user' => auth()->user()->only(['id', 'name', 'email']),
                'roles' => auth()->user()->getRoleNames(),
                'can' => auth()->user()->getAllPermissions()->pluck('name')
            ]
        ]);
    })->name('admin.dashboard');
});




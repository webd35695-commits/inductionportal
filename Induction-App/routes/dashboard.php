<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return inertia('Dashboard/Index');
})->middleware(['auth', 'verified'])->name('dashboard');

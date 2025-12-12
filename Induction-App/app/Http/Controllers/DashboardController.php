<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Routing\Controller as BaseController;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(PermissionMiddleware::class.':dashboard.view', ['only' => ['index']]);
    }

    public function index()
    {
        return Inertia::render('Dashboard/Admin/Index');
    }
}

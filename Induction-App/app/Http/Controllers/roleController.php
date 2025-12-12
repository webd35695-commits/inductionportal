<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

use function Termwind\render;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;


class roleController extends Controller
{
    public function index()
    {
        return inertia::render('Dashboard/Admin/Setting/Roles/Index', ['Roles' => Role::all()]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Admin/Setting/Roles/Create', ['Permissions' => permission::all()]);
    }

    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name]);

        $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();

        $role->syncPermissions($permissions);

        return redirect()->route('Roles.index')
            ->withSuccess('New role is added successfully.');
    }

    public function show($id)  // Get the ID directly
    {
        $role = Role::findOrFail($id);
        $rolePermissions = Permission::join("role_has_permissions", "permissions.id", "=", "role_has_permissions.permission_id")
            ->where("role_has_permissions.role_id", $id)
            ->pluck('name');


        return Inertia::render('Dashboard/Admin/Setting/Roles/Show', [
            'role' => $role,
            'rolePermissions' => $rolePermissions
        ]);
    }
}

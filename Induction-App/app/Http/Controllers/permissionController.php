<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use function Termwind\render;
use Inertia\Inertia;



class permissionController extends Controller
{
    public function index()
    {
        return inertia::render('Dashboard/Admin/Setting/Permission/Index', ['Permissions' => permission::all()]);
    }
    public function create()
    {
        return Inertia::render('Dashboard/Admin/Setting/Permission/Create');
    }

      public function store(Request $request)
    {
        try {
            if (permission::where('name', $request->input('name'))->exists()) {
                $request->session()->flash('message.level', 'danger');
                $request->session()->flash('message.content', 'Sorry..! Permission is already exists.');
                return back();
            } else {

                $permission = new permission;
                $permission->name = $request->input('name');
                $permission->guard_name  = 'web';
                $permission->save();


                if ($permission) {
                     return redirect()->route('Permissions.index')
            ->with('message', 'Permission Added Successfully.');
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withSuccess('Something went wrong.Please try again');
        }
    }
public function destroy(permission $permission)
    {

        $permission->delete();

        return redirect()->route('Permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}

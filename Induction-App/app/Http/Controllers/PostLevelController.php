<?php

namespace App\Http\Controllers;

use App\Models\post_level;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostLevelController extends Controller
{
    //
    public function index()
    {
$post_levels = post_level::orderBy('name', 'ASC')->get();

        return Inertia::render('Dashboard/Admin/PostLevel/index', [
            'post_levels' => $post_levels ,
        ]);
    }
    public function create()
    {
        return Inertia::render('Dashboard/Admin/PostLevel/create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        post_level::create($request->all());
        
        return redirect()->route('post_levels.index')->with('message', 'Post Level created successfully.');
    }
    public function show(post_level $post_level)
    {
        return Inertia::render('Dashboard/Admin/PostLevel/show', [
            'post_level' => $post_level,
        ]);
    }
    public function edit(post_level $post_level)
    {
        return Inertia::render('Dashboard/Admin/PostLevel/edit', [
            'post_level' => $post_level,
        ]);
    }
    public function update(Request $request, post_level $post_level)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $post_level->update($request->all());
        return redirect()->route('post_levels.index')->with('message', 'Post Level updated successfully.');
    }
    public function destroy(post_level $post_level)
    {
        $post_level->delete();
        return redirect()->route('post_levels.index')->with('message', 'Post Level deleted successfully.');
    }
}

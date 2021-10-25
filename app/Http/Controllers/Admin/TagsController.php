<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return view('admin.content-list.tags.index')->with('tags', $tags);
    }

    public function create()
    {
        return view('admin.content-list.tags.create');
    }

    public function store(Request $request)
    {
        $tags = Tag::create($request->only('name'));
        return redirect('/admin/tags-filter')->with('status', 'article added successfully');
    }

    public function edit(Tag $tag)
    {
        $tags = Tag::all(); 
        return view('admin.content-list.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $tag->update($request->only('name'));
        
        return redirect()->route('dashboard.tagsindex');
        
    }
    public function destroy(Tag $tag)
    {
        $tag->delete();
        
        return redirect()->route('dashboard.tagsindex');
    }


}


<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContentCategory;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = ContentCategory::orderBy('created_at', 'desc')->get();
        return view('admin.content.index')->with('categories', $categories);
    }

    public function create()
    {
        return view('admin.content.create');
    }

    public function store(Request $request)
    {
        $category = new ContentCategory();
        $category->name = $request->input('name');
        $category->className = $request->input('className');
        $category->category_descr = $request->input('category_descr');
        $category->save();

        if ($request->hasFile('category_img')) {
            $category->addMedia($request->file('category_img'))->toMediaCollection('catimg');
        }

        return redirect()->route('category.index')->with('status', 'category added successfully');
    }

    public function edit(ContentCategory $category)
    {
        // return $category->category_descr;
        // dd($category->url);
        return view('admin.content.edit', compact('category'));
    }

    public function update(Request $request, ContentCategory $category)
    {
        ContentCategory::where('id', $category->id)->update([
            'name' => $request->input('name'),
            'className' => $request->input('className'),
            'category_descr' => $request->input('category_descr'),
        ]);
        if ($request->hasFile('category_img')) {
            $category->addMedia($request->file('category_img'))->toMediaCollection('catimg');
        }
        return redirect()->route('category.index')->with('status', 'category added successfully');
    }

    public function delete(ContentCategory $category)
    {
        $category->delete();
        return redirect()->route('category.index')->with('status', 'video added successfully');
    }

}


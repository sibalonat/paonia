<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use App\Models\Multimedia;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use App\Like;
use Str;

class MultimediasController extends Controller
{
    //
    public function index()
    {
        $tags = Tag::all();
        $category = ContentCategory::all();
        $content_list = Multimedia::orderBy('created_at', 'desc')->get();

        return view('admin.content-list.index')
        ->with('content_category', $category)
        ->with('content_list', $content_list)
        ->with('tags', $tags);
    }

    public function create()
    {
        $tags = Tag::all();
        $category = ContentCategory::all();
        $content_list = Multimedia::all();

        return view('admin.content-list.create')
        ->with('content_category', $category)
        ->with('content_list', $content_list)
        ->with('tags', $tags);
    }

    public function store(Request $request)
    {
        $contlist = new Multimedia();
        $contlist->category_id = $request->input('category_id');
        $contlist->name = $request->input('name');
        $contlist->description = $request->input('description');
        $contlist->duration = $request->input('duration');
        if($request->file('file')){
            $file = $request->file('file');
            $filename = time() . '.' . $request->file('file')->getClientOriginalExtension();
            $file->move('storage/multimedia', $filename);
            $contlist->file = $filename;
        }
        //spatie
        $contlist->save();

        if ($request->hasFile('podcast_image')) {
            $contlist->addMedia($request->file('podcast_image'))->toMediaCollection('podcastimg');
        }
        // tags
        $contlist->tags()->sync($request->input('tags'));
        return redirect()->route('multimedia.index')->with('status', 'Multimedia Added');

    }

    public function editMultimedia(Multimedia $multimedia)
    {
        $tags = Tag::get();
        $category = ContentCategory::get();
        return view('admin.content-list.edit', compact('tags', 'category', 'multimedia'));
    }

    public function update(Request $request, Multimedia $multimedia)
    {
        $contlist = Multimedia::find($multimedia->id);
        $contlist->category_id = $request->input('category_id');
        $contlist->name = $request->input('name');
        $contlist->description = $request->input('description');
        $contlist->duration = $request->input('duration');
        if($request->file('file')){
            $file = $request->file('file');
            $filename = time() . '.' . $request->file('file')->getClientOriginalExtension();
            $file->move('storage/multimedia', $filename);
            $contlist->file = $filename;
        };

        //spatie
        $contlist->save();

        if ($request->hasFile('podcast_image')) {
            $contlist->addMedia($request->file('podcast_image'))->toMediaCollection('podcastimg');
        }
        // tags
        $contlist->tags()->sync($request->input('tags'));
        
        return redirect()->route('multimedia.index')->with('status', 'Multimedia Added');
    }

    public function delete($id)
    {
        $multimedia = Multimedia::findOrFail($id);
        $multimedia->delete();
        //Refresh the model data to be returned
        $tags = Tag::all();
        $category = ContentCategory::all();
        $content_list = Multimedia::all();

        return view('admin.content-list.index')->with('content_category', $category)->with('content_list', $content_list)->with('tags', $tags);
    }

    public function show($id)
    {
        $item = Multimedia::find($id);
        return view('details',compact('item'));
    }

    public function podcast($id)
    {
        $item = Multimedia::find($id);
        return view('frontend.articles-list.podcast_article',compact('item'));
    }

    
    public function likePost($id)
    {
        // here you can check if product exists or is valid or whatever

        $this->handleLike('App\Models\Multimedia', $id);
        return redirect()->back();
    }

    public function handleLike($type, $id)
    {
        $existing_like = Like::withTrashed()->whereLikeableType($type)->whereLikeableId($id)->whereUserId(Auth::id())->first();

        if (is_null($existing_like)) {
            Like::create([
                'user_id'       => Auth::id(),
                'likeable_id'   => $id,
                'likeable_type' => $type,
            ]);
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
            } else {
                $existing_like->restore();
            }
        }
    }

}

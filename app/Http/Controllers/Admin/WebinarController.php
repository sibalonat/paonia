<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Webinar;
use App\Models\ContentCategory;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use App\Like;
use Str;

class WebinarController extends Controller
{
    public function index()
    {
        $webinars = Webinar::orderBy('created_at', 'desc')->get();
        // dd($webinars);

        return view('admin.content-list.webinar.index', compact('webinars'));
    }

    public function create()
    {
        $content_category = ContentCategory::all();
        return view('admin.content-list.webinar.create', compact('content_category'));
    }

    public function edit(Webinar $webinar)
    {
        // $webinar = Webinar::find($id);
        // dd($webinar->url);
        $content_category = ContentCategory::all();
        return view('admin.content-list.webinar.edit')->with('webinar', $webinar)->with('content_category', $content_category);
    }

    public function store(Request $request)
    {
        $webinaro = new Webinar();
        $webinaro->category_id = $request->input('category_id');
        $webinaro->name = $request->input('title');
        $webinaro->content = $request->input('post_content');
        $webinaro->event = $request->input('event');
        $webinaro->calendar = $request->input('calendar');

        $webinaro->save();
        if ($request->hasFile('webinar_image')) {
            $webinaro->addMedia($request->file('webinar_image'))->toMediaCollection('webinarimg');
        }

        return redirect()->route('webinar.index')->with('status', 'webinar added successfully');
    }

    
    public function update(Request $request, Webinar $webinar)
    {
        Webinar::where('id', $webinar->id)->update([
            'category_id' => $request->input('category_id'),
            'name' => $request->input('title'),
            'content' => $request->input('post_content'),
            'event' => $request->input('event'),
            'calendar' => $request->input('calendar'),
        ]);
        if ($request->hasFile('webinar_image')) {
            $webinar->addMedia($request->file('webinar_image'))->toMediaCollection('webinarimg');
        }
        return redirect()->route('webinar.index')->with('status', 'video added successfully');
    }

    public function delete(Webinar $webinar)
    {
        $webinar->delete();
        return redirect()->route('webinar.index');
    }

    public function likePost($id)
    {
        // here you can check if product exists or is valid
        $this->handleLike('App\Models\Webinar', $id);
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


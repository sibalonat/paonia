<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Multimedia;
use Illuminate\Http\Request;
use App\Models\ContentCategory;
use App\Http\Controllers\Controller;
use App\Models\Video;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $tags = Tag::all();
        $videos = Video::all();
        // $video
        // $content_list = Multimedia::all();

        //dd($content_list);

        return view('admin.content-list.videos.index', compact('videos'));
        // return view('admin.content-list.videos.index')
        // ->with('content_category', $category)
        // ->with('content_list', $content_list)
        // ->with('tags', $tags);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $tags = Tag::get();
        $category = ContentCategory::get();
        return view('admin.content-list.videos.create', compact(['category', 'tags']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $video = new Video();
        $video->category_id = $request->input('category_id');
        $video->name = $request->input('title');
        $video->vimeourl = $request->input('vm_link');
        $video->youtubeurl = $request->input('yt_link');
        $video->bmurl = $request->input('bm_link');
        $video->videodescription = $request->input('videodescription');
        $video->save();

        // $category->save();
        if ($request->hasFile('video_img')) {
            $video->addMedia($request->file('video_img'))->toMediaCollection('vidimg');
        }

        $video->tags()->sync($request->input('tags'));

        return redirect()->route('video.index')->with('status', 'video added successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edited(Video $video)
    {
        $category = ContentCategory::get();
        
        $tags = Tag::get();
        // dd($video->tags);
        return view('admin.content-list.videos.edit', compact(['video', 'tags', 'category']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        //
        Video::where('id', $video->id)->update([
            'category_id' => $request->input('category_id'),
            'name' => $request->input('title'),
            'vimeourl' => $request->input('vm_link'),
            'youtubeurl' => $request->input('yt_link'),
            'bmurl' => $request->input('bm_link'),
            'videodescription' => $request->input('videodescription'),
        ]);
        if ($request->hasFile('video_img')) {
            $video->addMedia($request->file('video_img'))->toMediaCollection('vidimg');
        }
        $video->tags()->sync($request->input('tags'));

        return redirect()->route('video.index')->with('status', 'video added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        //
        $video->delete();
        return redirect()->route('video.index');
    }
}

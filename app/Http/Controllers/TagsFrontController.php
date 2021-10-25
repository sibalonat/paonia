<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Video;
use App\Models\Article;
use App\Models\Multimedia;
use Illuminate\Http\Request;

class TagsFrontController extends Controller
{
    //

    public function index(Tag $tag)
    {
        $articoli = Article::with('favorites')->with(['tags' => function($query) {
            return $query->first();
        }])->get();
        $multimedia = Multimedia::with('favorites')->with(['tags' => function($query) {
            return $query->first();
        }])->get();
        $video = Video::with('favorites')->with(['tags' => function($query) {
            return $query->first();
        }])->get();

   	    $collectarticoli = collect($articoli);
        $collectpodcast = collect($multimedia);
        $collectvideo = collect($video);
        $collection = $collectarticoli->concat($collectpodcast)->concat($collectvideo);
        $grouped = $collection->groupBy($tag->id)->sortByDesc('created_at');

        $grouped->all();

        foreach ($grouped as $key => $singola) {
            $singola;
        }
        $alleight = $singola->take(8);


        return view('frontend.articles-list.tagsindex', compact(['alleight', 'tag']));
    }
}

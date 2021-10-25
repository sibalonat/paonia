<?php

namespace App\Http\Controllers;
use App\Like;
use App\Models\Tag;
use App\Models\User;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\Article;
use App\Models\Webinar;

use App\Models\Sessions;

use App\Models\Multimedia;
use App\Models\Video;
// use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class ArticleFrontController extends Controller
{

    // public function singleArticle(Article $article, Tag $tag)
    // {
    //     $relatedArticles = Article::whereHas('tags', function ($q) use ($article) {
    //         return $q->whereIn('name', $article->tags->pluck('name'));
    //     })
    //     ->where('id', '!=', $article->id) // So you won't fetch same post
    //     ->take(3)
    //     ->get();
    

    //     $articlestags = $article->tags;


    //     return view('frontend.articles-list.singlearticle', compact(['article', 'relatedArticles', 'articlestags']));
    // }

    // public function allContent(Request $request, Tag $tag, Article $article, Sessions $s, Thread $thread)
    // {

    //     $allArticles = Article::orderBy('updated_at','DESC')->paginate(4);
    //     $allMultimedia = Multimedia::orderBy('updated_at','DESC')->paginate(4);
    //     $allVideo = Video::orderBy('updated_at','DESC')->paginate(4);
    //     $allWebinars = Webinar::all();

        // if ($request->ajax()) {
        //     // dd($request->input("model"));
        //     switch($request->input("model")) {
        //         case "Videos":
        //             return view('frontend.articles-list.partials.videos-load', ['allVideo' => $allVideo])->render();
        //             break;
        //         case "Articles":
        //             return view('frontend.articles-list.partials.articles-load', ['allArticles' => $allArticles])->render();
        //             break;
        //         case "Podcasts":
        //             return view('frontend.articles-list.partials.podcasts-load', ['allMultimedia' => $allMultimedia])->render();
        //             break;
        //     }

        // }

    //     $tags = Tag::all();

    //     $t = $thread::all();

    //     $threads = Thread::latest()->get();
    //     $threads3 = Thread::latest()->take(3)->get();
    //     $replies = Reply::all();
    //     $user = User::all();



    //     return view('frontend.articles-list.allcontents', compact([
    //         'allArticles',
    //         'tags',
    //         'allWebinars',
    //         'allMultimedia',
    //         'allVideo',
    //         'threads',
    //         'threads3',
    //         'replies',
    //         'user',
    //         't'
    //     ]));
    // }

    public function searchArticles(Request $request) {

        $articles = Article::where('name', 'like', '%' . $request->get('searchQuery') . '%')->limit(3)->get();

        return json_encode($articles);

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

<?php

namespace App\Http\Controllers;
use App\Models\Tag;
use App\Models\Like;
use App\Models\User;
use App\Models\Claps;
use App\Models\Reply;
use App\Models\Video;
use App\Models\Thread;
use App\Models\Article;
use App\Models\Comment;
use App\Models\MedUser;
use App\Models\Webinar;
use App\Models\Sessions;
use App\Models\Multimedia;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

// use DB;
use App\Models\ContentCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Xetaio\Mentions\Parser\MentionParser;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Notifications\DatabaseNotification;

class HomeController extends Controller
{

    public function __construct()
    {
        // $this->middleware('medikey')->except('index');
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user() || Session::get('IS_LOGGED') == 1) {
            // return view('home');
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function articlesIndex(Tag $tag, Article $article, Sessions $s, Thread $thread, MedUser $meduser)
    {
        $allArticles = Article::latest()->get()->take(1);
        $allMultimedia = Multimedia::where('category_id', '3')->latest()->get()->take(1);
        $allVideo = Multimedia::where('category_id', '1')->latest()->take(1)->get();
        $tags = Tag::all();;
        $allWebinars = Webinar::take(3)->get();
        $singleWebinar = Webinar::latest()->take(1)->get();
        $tags = Tag::all();

        if(auth()->user()){
            $userName = auth()->user()->nome;
            $userReplies = Reply::where('user_id', auth()->user()->id )->count();
            $allMentions = auth()->user()->unreadNotifications()->count();
            $allThreads = Thread::where(['user_id' => auth()->user()->id, 'published' => 1 ])->count();
            $allUserReplies = Reply::where(['user_id' => auth()->user()->id])->count();
            $totalDrafts = Thread::where(['published' => 0 ])->count();

        } else{
            $userName = [];
            $userReplies = 'Not Defined';
            $allMentions = 'Not Defined';
            $allThreads = 'Not Defined';
            $allUserReplies  = 'Not Defined';
            $totalDrafts = 'Not Defined';
        }

        // Thread

        // $t = $thread::all();
        $threads = Thread::latest()->take(3)->get();
        // $threads3 = Thread::latest()->take(3)->get();
        $replies = Reply::all();

        // here threads end


        $categories = ContentCategory::all();

	    $a = Article::latest()->limit(2)->get();
        $m = Multimedia::latest()->limit(2)->get();
        $merged = Arr::collapse( [$a, $m] );


        $numComments = Reply::where(['thread_id' => 1])->count();

        // $numLikes =  Claps::where(['claped_id' => 1])->count();

        \DB::statement("SET SQL_MODE=''");

        // FacadesDB
        $count = Thread::count();

        if($count >= 1)
            $topthreads = Reply::select('threads.title')->join('threads', 'threads.id', '=', 'replies.thread_id')->groupBy('thread_id')->selectRaw('count(*) as total_replies, thread_id')->take(4)->get();
        else
            $topthreads = [];

            $articoli = Article::with('category')->latest()->get();
            $multimedia = Multimedia::with('category')->latest()->get();
            $video = Video::with('category')->latest()->get();

            // $collectarticoli = collect($articoli);
            // $collectpodcast = collect($multimedia);
            // $collectvideo = collect($video);
            // $collectcontenuti = $collectarticoli->concat($collectpodcast)->concat($collectvideo);
            $collectcontenuti = $articoli->concat($multimedia)->concat($video);

            // dd($collection);
            $allcontenuti = $collectcontenuti->take(8);
            $allcontenuti->all();
            // dd($allcontenuti);
            // $collected = collect($allcontenuti);
            // $collected->dump();


        return view('home', compact(
            'userName',
            'allWebinars',
            'topthreads',
            'numComments',
            'thread',
            'allArticles',
            'article',
            'tags',
            'userReplies',
            'allMentions',
            'allThreads',
            'allUserReplies',
            'totalDrafts',
            'categories',
            'merged',
            'count',
            'replies',
            'threads',
            'singleWebinar',
            'allVideo',
            'allMultimedia',
            'allcontenuti'
        ));

    }

    public function searchArticles(Request $request)
    {

        $articles = Article::where('name', 'like', '%' . $request->get('searchQuery') . '%')->limit(3)->get();
        $webinari = Webinar::where('name', 'like', '%' . $request->get('searchQuery') . '%')->limit(3)->get();

        $searchResults = $articles->concat($webinari);


        return json_encode($searchResults);

    }

    public function addComment($id, Request $request)
    {
        $articoli = Article::with('category')->latest()->get();
        $multimedia = Multimedia::with('category')->latest()->get();
        $video = Video::with('category')->latest()->get();

        $collectarticoli = collect($articoli);
        $collectpodcast = collect($multimedia);
        $collectvideo = collect($video);
        $collectcontenuti = $collectarticoli->concat($collectpodcast)->concat($collectvideo);

        $dsobjct = $collectcontenuti->filter(function ($item) use ($id) {
            return $item->id == $id;
        })->first();


        $comment = $dsobjct->comments()->create([
            'comment' => $request->comment,
            'user_id' => Auth::user()->id,
            'parent_id' => $request->input('parent_id')
        ]);

        $parser = new MentionParser($comment);
        $content = $parser->parse($comment->comment);

        $comment->comment = $content;
        $comment->save();


        return back();
    }

    public function addLikeComment(Comment $comment)
    {
        $comment->like();

        return back();
    }

    public function removeLikeComment(Comment $comment)
    {
        $comment->unlike();

        return back();
    }


    public function singleContent($id, $slug, Request $request)
    {

        $articoli = Article::with('category')->latest()->get();
        $multimedia = Multimedia::with('category')->latest()->get();
        $video = Video::with('category')->latest()->get();

        $collectarticoli = collect($articoli);
        $collectpodcast = collect($multimedia);
        $collectvideo = collect($video);
        $collectcontenuti = $collectarticoli->concat($collectpodcast)->concat($collectvideo);

        $dsobjct = $collectcontenuti->filter(function($item) use($id) {
            return $item->id == $id;
        })->first();
        

        // comments section 
        $comments = $dsobjct->comments()->with('owner')->orderBy('created_at', 'desc')->get()->threaded();
        $dsobjct->load('comments.owner');



        $relatedArticles = Article::whereHas('tags', function ($q) use ($dsobjct) {
            return $q->whereIn('name', $dsobjct->tags->pluck('name'));
        })
        ->where('id', '!=', $dsobjct->id)
        ->take(3)
        ->get();

        $relatedVideos = Video::whereHas('tags', function ($q) use ($dsobjct) {
            return $q->whereIn('name', $dsobjct->tags->pluck('name'));
        })
        ->where('id', '!=', $dsobjct->id)
        ->take(3)
        ->get();

        $relatedPodcast = Multimedia::whereHas('tags', function ($q) use ($dsobjct) {
            return $q->whereIn('name', $dsobjct->tags->pluck('name'));
        })
        ->where('id', '!=', $dsobjct->id)
        ->take(3)
        ->get();

        return view('frontend.articles-list.homechunk.singlechunk', compact('dsobjct', 'id', 'relatedArticles', 'relatedVideos', 'relatedPodcast', 'comments'));
    }

    public function concatLike($id)
    {
        $articoli = Article::with('category')->latest()->get();
        $multimedia = Multimedia::with('category')->latest()->get();
        $video = Video::with('category')->latest()->get();

        $collectarticoli = collect($articoli);
        $collectpodcast = collect($multimedia);
        $collectvideo = collect($video);
        $collectcontenuti = $collectarticoli->concat($collectpodcast)->concat($collectvideo);


        $dsobjct = $collectcontenuti->filter(function($item) use($id) {
            return $item->id == $id;
        })->first();

        $dsobjct->like();
        return back();
    }

    public function deleteClap($id)
    {
        $articoli = Article::with('category')->latest()->get();
        $multimedia = Multimedia::with('category')->latest()->get();
        $video = Video::with('category')->latest()->get();

        $collectarticoli = collect($articoli);
        $collectpodcast = collect($multimedia);
        $collectvideo = collect($video);
        $collectcontenuti = $collectarticoli->concat($collectpodcast)->concat($collectvideo);


        $dsobjct = $collectcontenuti->filter(function($item) use($id) {
            return $item->id == $id;
        })->first();

        $dsobjct->unlike();
        return back();
    }


}

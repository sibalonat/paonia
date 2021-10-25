<?php

//event calendar mail to, opens default mail

namespace App\Http\Controllers;
use App\Models\Tag;
use App\Models\User;
use App\Models\Claps;
use App\Models\Reply;
use App\LikeThread;
use App\Models\Thread;

use App\Models\BestReply;
use App\Models\TagThread;
//use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Thread $thread)
    {
        $tags = Tag::all();


        $t = $thread::all();


        $threads = Thread::latest()->get();
        $threads3 = Thread::latest()->take(3)->get();
        $replies = Reply::all();
        $user = User::all();

        if (Auth::check()) { // -- Check if the user is logged in --

            if(auth()->user()->unreadNotifications()->count() > 0) {

                $notification = auth()->user()->unreadNotifications; //->where('id', $id);

                return view('frontend.forum.threads.index', ['threads' => $threads, 'tags' => $tags, 'threads3' => $threads3, 'replies' => $replies, 'user' => $user, 't' => $t, 'notification' => $notification]);
            }

            return view('frontend.forum.threads.index', ['threads' => $threads, 'tags' => $tags, 'threads3' => $threads3, 'replies' => $replies, 'user' => $user, 't' => $t]);


        }else{
            //return ['threads' => $threads, 'threads3' => $threads3, 'replies' => $replies, 'user' => $user, 't' => $t ]; //Return data in JSON format
            return redirect('login');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $tags = Tag::all();
        // Tag

        if (Auth::check()) {
            return view('threads.create', compact('tags'));
        }
        else{
            $request->session()->flash('error_message', 'You are not logged in');
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, Thread $thread)
    {



        //dd($request->input('tags'));
        if (Auth::check()) {

            $inputs = $request->validate([
                    'title' => 'required | max:600',
                    'body' => 'required',
                    'tags' => 'required'
            ]);


            if($request->input('draft'))
            {
                $query = $thread->create([
                    'user_id' => auth()->id(),
                    // 'category_id' => 1, // Satic to test, can otionally add to be able to chose the category on the thread
                    'title' => request('title'),
                    // 'slug' => request('title'),
                    'body' => request('body'),
                    'author' => auth()->user()->name,
                    'published' => 0
                ]);

                foreach($request->input('tags') as $key)
                {
                    TagThread::create([
                        'thread_id' => $query->id,
                        'tag_id' => $key
                    ]);
                }
            }
            else{
                $query = $thread->create([
                    'user_id' => auth()->id(),
                    // 'category_id' => 1, // Satic to test, can otionally add to be able to chose the category on the thread create modal
                    'title' => request('title'),
                    // 'slug' => request('title'),
                    'body' => request('body'),
                    'author' => auth()->user()->name,
                    'published' => 1
                ]);

                foreach($request->input('tags') as $key) {

                    //dd($request->input('tags')[$key]);
                    TagThread::create([
                        'thread_id' => $query->id,
                        'tag_id' => $key
                    ]);

                }
            }
            //return redirect($thread->path());
            return redirect('threads');
       }
       else
        {
            $request->session()->flash('error_message', 'The thread could not be Created, please log in');
        }

        //return redirect($thread->path());

    }

    /**
     * Display the specified resource.
     * @ param  int  $id  Notification ID
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Thread $thread, Reply $replies, DatabaseNotification $notification_id,  BestReply $bs, Request $request)
    {
        //dd($thread->replies[0]->published);

        $threadAll = Thread::all();

        $numComments = Reply::where(['thread_id' => $thread->id])->count();

        if (Auth::check()) {
            if(auth()->user()->unreadNotifications()->count() > 0){
                $id = auth()->user()->unreadNotifications[0]->id;
                $Notification = auth()->user()->unreadNotifications->where('id', $id);

                if($Notification){
                    $Notification->markAsRead();
                }
            }
        }
        $tags = Tag::join('tag_thread', 'tag_thread.tag_id', '=', 'tags.id')
        ->select('tag_thread.tag_id', 'tag_thread.thread_id', 'tags.name')
        ->where('tag_thread.thread_id', $thread->id)
        ->get();
           $tagThreads  = TagThread::where(function ($query) use($tags) {
            for ($i = 0; $i < count($tags); $i++){
                $query->where('tag_id', $tags[$i]->tag_id );
            }
        })
        ->get();
        $relatedThreads  = Thread::where(function ($query) use($tagThreads) {
            for ($i = 0; $i < count($tagThreads); $i++){
                $query->orwhere('id', $tagThreads[$i]->thread_id );
            }
        })
        ->where('id', '!=', $thread->id) // So you won't fetch same post
        ->take(2)
        ->get();

        $threadAll = Thread::all();
        $usr_names = User::get('nome');
        $user_names = json_encode($usr_names);

        //dd( $user_names);

        return view('frontend.forum.threads.show', ['thread' => $thread, 'threadAll' => $threadAll, 'relatedThreads' => $relatedThreads, 'tg' => $tags, 'replies' => $replies, 'bs' => $bs, 'numComments' => $numComments, 'user_names' => $user_names]);
    }

    public function drafts() //$id
    {

        $threads = Thread::latest()->get();

        $draft_count = Thread::where('published',0)->count();

        return view('threads.drafts')
            ->with('threads', $threads)
            ->with('draft_count', $draft_count);
            //->with('thread_id', $thread_id);
    }


    public function publish(Thread $thread, Request $request)
    {
        $draft_id = $request->input('draft_id');

        //if(auth()->user()->id == $thread->user_id){

        $thread->where('id', $draft_id)->update(array('published' => 1));


        $request->session()->flash('flash_message', 'Your forum thread was published successfuly');

        return redirect($thread->path());

    }

    public function un_publish(Thread $thread, Request $request)
    {
        $draft_id = $request->input('draft_id');

        $thread->where('id', $draft_id)->update(array('published' => 0));


        $request->session()->flash('flash_message', 'Your forum thread was un published successfuly');

        return redirect($thread->path());
    }


    public function save_as_draft(Thread $thread, Request $request)
    {
        $draft_id = $request->input('draft_id');

        $thread->where('id', $draft_id)->update(array('published' => 0));


        $request->session()->flash('flash_message', 'Your forum thread was published successfuly');

        return redirect($thread->path());
    }

    public function update_view( $id) //User $user Request $request,
    {

        $thread_id = Thread::find($id);

        return view('frontend.forum.threads.update_view')->with('thread_id', $thread_id);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread) //If accessd through the http get method; it is not authorized except the post method
    {
        //thread_id, user_id, body
        if(auth()->user()->id === $thread->user_id){ //if the user has created the post then they can delete the thread

            $all_threads = $thread->all();

            $thread->body = $request->input('title');
            $thread->body = $request->input('body');

            $thread->save();

            $request->session()->flash('flash_message', 'The thread was Updated successfuly');
        }
        else
            {
               $request->session()->flash('error_message', 'The thread could not be Updated, only owner allowed');
            }
        return redirect($thread->path());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    //public function destroy(Thread $thread, Request $request)
    public function delete(Thread $thread, Request $request) //User $user,
    {

        //Prevent other users from deleting and going futher, not owners
        //if(auth()->user()->id !== $thread->user_id){ //In this negation statement the users who are looged in but not owners of the thread can delete, notice the question mark
        if(auth()->user()->id == $thread->user_id){ //if the user has created the post then they can delete the thread


            $thread->delete();
            $request->session()->flash('flash_message', 'The thread was deleted');
        }
        else
        {
            $request->session()->flash('error_message', 'The thread could not be deleted, not the original owner');
        }

        return view('frontend.forum.threads.delete'); //Redirect to the view to refresh the page and show flash message to the user
    }

    public function likePost($id)
    {
        // here you can check if product exists or is valid or whatever

        $this->handleLike('App\Models\Thread', $id);
        return redirect()->back();
    }

    public function handleLike($type, $id)
    {
        $existing_like = LikeThread::withTrashed()->whereLikeableType($type)->whereLikeableId($id)->whereUserId(Auth::id())->first();

        if (is_null($existing_like)) {
            LikeThread::create([
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

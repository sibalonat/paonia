<?php

namespace App\Http\Controllers;

//use App\Reply;
//use App\Thread;
use App\Models\User;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Notifications\UserMentioned;
use App\Notifications\LikesNottification;

class ReplyController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Thread $thread, Request $request)
    {

        if (Auth::check()) {

            $inputs = $request->validate([
                'body' => 'required | max: 3000'
            ]);
            //Data aray used for mentions
            $replyData = [
                'body' => $request->body,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->nome,
                'thread_id' => $thread->id,
                'thread_path' => $thread->path(),
                'thread_title' =>   $thread->title
            ];

            if($request->has('bozze_btn')){ 
                $thread->addReply([
                    'thread_id' => $thread->id,
                    'user_id' => auth()->id(),
                    'body' => $request->body
                    //'published' field => default = 0
                ]);
            }
            else  if($request->has('comment_btn')){ 
                $thread->addReply([
                    'thread_id' => $thread->id,
                    'user_id' => auth()->id(),
                    'body' => $request->body,
                    'published' => 1
                ]);
            }

            preg_match_all('/@([A-Z])\w+\s\w+/',  $inputs['body'], $matches ); //Match all First and Last name occurences starting with a capital letter
            $names = $matches[0];

            //Notify each mentioned user
            foreach($names as $name){
               
                $name = substr($name, 1);
            
                $user = User::where('nome', $name)->get();

                if($user->count() > 0){

                    foreach($user as $u)
                    {      
                        //dd($u);  
                        $u->notify(new UserMentioned($replyData));   
                    }

                }         

            }

            return back();
        }
        else
        {
             $request->session()->flash('error_message', 'The reply could not be Created, please log in');
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Thread $thread)
    {
        //dd($thread);
        return view('threads.show', compact('thread'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function delete(Reply $reply, Request $request)
    {
        if(auth()->user()->id !== $reply->user_id)

        //Delete the thread
        $reply->delete();
        $request->session()->flash('flash_message', 'The reply was deleted');

        return back();
    }

    public function comment_likes(Thread $thread)
    {
        $replyThreadTitle =  $thread->find($thread->id)->title;

        $comentsData = [
            'user_id' => auth()->user()->id, //request('user_id')
            'user_name' => auth()->user()->nome,
            'thread_path' => $thread->path(),
            'thread_title' =>   $replyThreadTitle
        ];
        $thread->favorite();
        $getThread = $thread->where('id', $thread->id )->first();

        $user = User::where('id', $getThread->user_id)->first();

        if($user){
            $user->notify(new LikesNottification($comentsData));
        }
        return back();
    }
}



  /*     preg_match_all('/\@([^\s\.]+)/',  $inputs['body'], $matches ); 
            $names = $matches[1];
            foreach($names as $name){
                $user = User::whereName($name)->first();
                if($user){
                    $user->notify(new UserMentioned($replyData));
                }
            } */

            //Notify each mentioned user
            //$user = Auth::user();
            //return $reply->load('owner');
            //return $reply->load($user);
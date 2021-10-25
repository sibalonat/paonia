<?php

namespace App\Http\Controllers;

use App\Models\BestReply;
use App\Models\Thread;
use App\Models\Reply;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class BestRepliesController extends Controller
{
    
    public function store(Request $request, Reply $r, Thread $thread, BestReply $bs, $reply)
    {

        //dd($request->input('thread_id'));

        $Rep = $bs->where(['reply_id' => $reply])->get();

        //dd($Rep->count());


        if (Auth::check()) 
        {
    
            if($Rep->count() > 0) //Is there a best in the reply?
            {

                foreach($Rep as $R){
                    //dd($R->user_id);
                    if(auth()->user()->id == $R->user_id){
                        $found = true;
                        //dd('T = ' . $found);
                    }
                    else
                    {
                        $found = false;
                        //dd('F = ' . $found);
                    }
                }

                if(!$found){

                    $bs->create([
                        'user_id' => auth()->id(),
                        'thread_id' => $request->input('thread_id'),
                        'reply_id' => $reply

                    ]);

                } 
                else
                {
                    $request->session()->flash('error_message', 'You have already \'Best Liked\' this reply! ');

                    //return view('frontend.forum.threads.best-replies');
                    
                    return redirect($thread->path() .'' . $request->input('thread_id'));


                }
            }
            else //There is no best in the reply; insert the best reply
            {

                $bs->create([
                    'user_id' => auth()->id(),
                    'thread_id' => $request->input('thread_id'),
                    'reply_id' => $reply

                ]);

                return redirect($thread->path() . '' . $request->input('thread_id'));

            }

        }
        else
        {
            $request->session()->flash('error_message', 'Please Login');
        }

       // dd($bs->where([]));

        //dd($r->thread_fcn()->get()[$reply]);
        //dd($r->thread_fcn()->get());

        //Reply $reply
        //dd(auth()->user()->id);
        //dd($reply[0]->user_id);
        //dd($reply->owner->name);
        
        //dd($thread->replies);
        //dd($id);
        //abort_if($reply->thread->user_id != auth()->user()->id, 401 );
        // $reply->thread->update(['best_reply_id' => $reply->id]);
        //$reply->thread->markBestReply($id);
        //$thread->markBestReply($reply);

        //dd($r->thread_fcn->markBestReply($reply));

        //dd($thread->replies[0]->owner->name);

        //foreach ($thread->replies as $reply)
      
            //dd($reply->owner->name);
        
        //dd($r->thread_fcn);

    }



}

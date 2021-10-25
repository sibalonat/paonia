<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class PodcastComments extends Model
{
    protected $fillable = array(
        'name',
        'comment',
        'user_id',
        'podcast_id'
   
    );
    
    
    public function replypodcast()
    {
        // return '/threads/' . $this->id;
        return $this->hasMany(ReplyPodcast::class);
    }

    public function likes()
    {
        return $this->morphToMany('App\Models\User', 'likeable')->whereDeletedAt(null);
    }

    public function getIsLikedAttribute()
    {
        $like = $this->likes()->whereUserId(Auth::id())->first();
        return (!is_null($like)) ? true : false;
    }
}

<?php

namespace App\Models;
use App\Models\User;
use Conner\Likeable\Likeable;
use App\Models\CommentCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Xetaio\Mentions\Models\Traits\HasMentionsTrait;


class Comment extends Model
{
    //
    protected $guarded = [];
    
    use Likeable, HasMentionsTrait;

    public function owner() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function newCollection(array $models = [])
    {
        return new CommentCollection($models);
    }

}

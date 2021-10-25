<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;

use Conner\Likeable\Likeable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Article extends Model implements HasMedia
{
    use HasMediaTrait, Likeable;
    

    protected $table = 'articles';
    protected $guarded = [];

    protected $appends = ['url'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function category()
    {
        // return $this->belongsTo(ContentCategory::class, 'category_id');
        return $this->belongsTo(ContentCategory::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getUrlAttribute()
    {
        $hasMedia = $this->getMedia('featuredimage')->first();
        return  $hasMedia != null ?
            $hasMedia->getUrl() : "";
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'opinionable');
    }

}

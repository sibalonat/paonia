<?php

namespace App\Models;

use Illuminate\Support\Str;
use Conner\Likeable\Likeable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Video extends Model implements HasMedia
{
    //
    use HasMediaTrait, Likeable;

    protected $table = 'videos';
    protected $guarded = [];

    protected $appends = ['url'];
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }


    public function category()
    {
        return $this->belongsTo(ContentCategory::class, 'category_id');
    }

    public function getUrlAttribute()
    {
        $hasMedia = $this->getMedia('vidimg')->first();
        return  $hasMedia != null ?
            $hasMedia->getUrl() : "";
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }


    public function comments()
    {
        return $this->morphMany(Comment::class, 'opinionable');
    }



    public function getRouteKeyName()
    {
        return 'slug';
    }

}

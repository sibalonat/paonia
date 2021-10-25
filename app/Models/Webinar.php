<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\DB;
// use Spatie\MediaLibrary\Models\Media;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Support\Facades\Auth;

class Webinar extends Model implements HasMedia
{

    use HasMediaTrait;

    protected $table = 'webinars';
    protected $guarded = [];



    protected $appends = ['url'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'opinionable');
    }


    public function content_category()
    {
        return $this->belongsTo(ContentCategory::class,'category_id', 'id')->withTimestamps();
    }


    public function getUrlAttribute()
    {
        $hasMedia = $this->getMedia('webinarimg')->first();
        return  $hasMedia != null ?
            $hasMedia->getUrl() : "";
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }

}

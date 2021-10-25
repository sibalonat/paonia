<?php

namespace App\Models;

use Illuminate\Support\Str;
use Conner\Likeable\Likeable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Multimedia extends Model implements HasMedia
{
     //
     use HasMediaTrait, Likeable;
   
     protected $table = 'multimedia';

     protected $guarded = [];
     protected $appends = ['url'];
     

 
     public function category()
     {
         return $this->belongsTo(ContentCategory::class, 'category_id');
     }
     public function setNameAttribute($value)
     {
         $this->attributes['name'] = $value;
         $this->attributes['slug'] = Str::slug($value);
     }

     public function tags()
     {
         return $this->belongsToMany(Tag::class);
         // return $this->belongsToMany(Tag::class, 'article_tag');
     }

     public function getRouteKeyName()
     {
         return 'slug';
     }

     public function getUrlAttribute()
     {
         $hasMedia = $this->getMedia('podcastimg')->first();
         return  $hasMedia != null ?
             $hasMedia->getUrl() : "";
     }

     public function comments()
     {
         return $this->morphMany(Comment::class, 'opinionable');
     }

}

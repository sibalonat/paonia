<?php


namespace App\Models;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;

use App\Models\Article;

class ContentCategory extends Model implements HasMedia
{
    //
    use HasMediaTrait;

    protected $guarded = [];

    
    protected $table = 'contentcategories';
    // protected $fillable = ['name'];

    protected $appends = ['url']; //Add image url/ available as an append => this $instance->url


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
        // $this->attributes['slug'] = Str::slug($value);
    }

    public function getUrlAttribute()
    {
        $hasMedia = $this->getMedia('catimg')->first();
        return  $hasMedia != null ?
            $hasMedia->getUrl() : "";
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }
    public function multimedia()
    {
        return $this->hasMany(Multimedia::class, 'category_id');
    }
    public function videos()
    {
        return $this->hasMany(Video::class, 'category_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
}


//namespace App\Models;
///use Illuminate\Support\Str;
//use Illuminate\Database\Eloquent\Model;

//class ContentCategory extends Model
//{
    //

    // protected $guarded = [];

    //protected $table = 'contentcategories';
    //protected $fillable = ['name'];

    //public function setNameAttribute($value)
    //{
        //$this->attributes['name'] = $value;
        //$this->attributes['slug'] = Str::slug($value);
        // $this->attributes['slug'] = Str::slug($value);
    //}
//}

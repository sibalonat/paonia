<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Media extends Model
{
    //
    protected $guarded = [];
    protected $table = 'media';
    // protected $fillable = ['category_id', 'title', 'description', 'file', 'duration'];

    public function category()
    {
        return $this->belongsTo(ContentCategory::class, 'category_id');
    }
    
}

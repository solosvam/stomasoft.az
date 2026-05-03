<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVideo extends Model
{
    protected $table = 'page_videos';
    public $timestamps = false;
    protected $hidden = [];

    protected $fillable = [
        'route',
        'title',
        'youtube_url',
        'youtube_id',
        'active',
    ];
}

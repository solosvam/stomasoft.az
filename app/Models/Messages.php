<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $table = 'messages';
    public $timestamps = false;
    protected $hidden = [];
    protected $fillable = [
        'fullname',
        'email',
        'phone',
        'subject',
        'message',
    ];
}

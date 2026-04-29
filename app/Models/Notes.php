<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $table = 'notes';
    public $timestamps = false;
    protected $hidden = [];
    protected $fillable = [
        'user_id',
        'note'
    ];
}

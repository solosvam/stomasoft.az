<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';
    public $timestamps = true;
    protected $hidden = [];
    protected $fillable = [
        'user_id',
        'partner_id',
        'product',
        'qty',
        'price',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class,'partner_id');
    }
}

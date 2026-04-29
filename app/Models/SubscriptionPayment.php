<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    protected $table = 'subscription_payments';
    public $timestamps = false;
    protected $hidden = [];
    protected $fillable = [
        'user_id',
        'amount',
        'period_from',
        'period_to',
        'created_at',
    ];
}

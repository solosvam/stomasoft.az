<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Services extends Model
{
    protected $table = 'services';
    public $timestamps = false;
    protected $hidden = [];

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'visible',
        'active'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->id());
            }
        });

        static::creating(function ($service) {
            if (auth()->check() && empty($service->user_id)) {
                $service->user_id = auth()->id();
            }
        });
    }
}

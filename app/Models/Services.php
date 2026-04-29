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
        'doctor_id',
        'name',
        'price',
        'active'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('doctor', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('doctor_id', auth()->id());
            }
        });

        static::creating(function ($service) {
            if (auth()->check() && empty($service->doctor_id)) {
                $service->doctor_id = auth()->id();
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialty extends Model
{
    protected $table = 'specialties';
    protected $fillable = [
        'key',
        'label',
        'location_type',
        'active',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'specialty_id');
    }
}

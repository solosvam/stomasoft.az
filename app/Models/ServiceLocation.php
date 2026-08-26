<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceLocation extends Model
{
    protected $table = 'service_locations';

    protected $fillable = [
        'type',
        'code',
        'name',
    ];

    public function sessionItems(): HasMany
    {
        return $this->hasMany(
            PatientServiceSessionItems::class,
            'location_id'
        );
    }
}

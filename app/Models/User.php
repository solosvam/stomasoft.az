<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use HasRoles;

    protected $table = 'user';
    public $timestamps = false;
    protected $fillable = [
        "name",
        "surname",
        "mobile",
        "login",
        "password",
        "is_active",
        "account_type",
        "subscription_ends_at",
        "parent_id",
        "specialty_id"
    ];
    protected $appends = ['fullname'];

    public function getFullnameAttribute()
    {
        return $this->name." ".$this->surname;
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'user_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Services::class, 'user_id');
    }

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class, 'user_id');
    }
    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class, 'user_id');
    }

    public function locationMap(): string
    {
        return $this->doctorProfile->specialty?->location_type ?? 'tooth_map';
    }

}

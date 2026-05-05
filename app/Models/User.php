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
        "isroot",
        "name",
        "surname",
        "mobile",
        "login",
        "password",
        "is_active",
        "is_doctor",
        "subscription_ends_at",
        "clinic_name",
        "clinic_address"
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
        return $this->hasMany(Services::class, 'doctor_id');
    }

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class, 'user_id');
    }

}

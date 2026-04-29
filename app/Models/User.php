<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

}

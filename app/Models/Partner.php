<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'partners';
    public $timestamps = false;
    protected $hidden = [];
    protected $fillable = [
        'user_id',
        'name',
        'mobile',
        'address',
        'type'
    ];

    public function doctorBalances()
    {
        return $this->hasMany(PartnerDoctorBalance::class,'partner_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

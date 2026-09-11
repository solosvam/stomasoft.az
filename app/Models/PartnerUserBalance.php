<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerUserBalance extends Model
{
    protected $table = 'partner_user_balances';
    public $timestamps = false;
    protected $fillable = ['partner_id','user_id','balance','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

}

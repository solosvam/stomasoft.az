<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerUserPatientBalance extends Model
{
    protected $table = 'partner_user_patient_balances';
    public $timestamps = false;
    protected $fillable = ['partner_id','user_id','patient_id','balance','updated_at'];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerDoctorBalance extends Model
{
    protected $table = 'partner_doctor_balances';
    public $timestamps = false;
    protected $fillable = ['partner_id','doctor_id','balance','updated_at'];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

}

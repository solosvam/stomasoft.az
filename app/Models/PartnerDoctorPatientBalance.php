<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerDoctorPatientBalance extends Model
{
    protected $table = 'partner_doctor_patient_balances';
    public $timestamps = false;
    protected $fillable = ['partner_id','doctor_id','patient_id','balance','updated_at'];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}

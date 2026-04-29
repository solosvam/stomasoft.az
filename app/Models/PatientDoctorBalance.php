<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientDoctorBalance extends Model
{
    protected $table = 'patient_doctor_balances';
    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'balance',
        'updated_at'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}

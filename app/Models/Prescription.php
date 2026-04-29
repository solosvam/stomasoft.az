<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $table = 'prescriptions';
    public $timestamps = false;
    protected $hidden = [];
    protected $fillable = [
        'doctor_id',
        'patient_id',
    ];

    public function items()
    {
        return $this->hasMany(PrescriptionItems::class, 'prescription_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}

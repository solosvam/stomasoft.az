<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $table = 'patients';
    public $timestamps = false;
    protected $hidden = [];
    protected $fillable = [
        'user_id',
        'name',
        'surname',
        'fin',
        'address',
        'sex',
        'bday',
        'mobile',
        'comment'
    ];

    public function getFullnameAttribute()
    {
        return $this->name." ".$this->surname;
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(PatientServiceSession::class, 'patient_id', 'id')->orderByDesc('id');
    }


    public function partnerBalances()
    {
        return $this->hasMany(PartnerDoctorPatientBalance::class, 'patient_id')
            ->where('balance','>',0);
    }

    public function doctorBalances()
    {
        return $this->hasMany(PatientDoctorBalance::class,'patient_id');
    }

    public function getTotalBalanceAttribute()
    {
        return $this->doctorBalances()
            ->where('balance','>',0)
            ->sum('balance');
    }

    public function files(): HasMany
    {
        return $this->hasMany(PatientFiles::class, 'patient_id', 'id')->orderByDesc('id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class,'patient_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientUserDeposit extends Model
{
    protected $table = 'patient_user_deposits';
    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'user_id',
        'deposit',
        'updated_at'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

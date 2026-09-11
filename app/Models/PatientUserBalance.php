<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientUserBalance extends Model
{
    protected $table = 'patient_user_balances';
    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'user_id',
        'balance',
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientDepositLedger extends Model
{
    protected $table = 'patient_deposit_ledger';
    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'user_id',
        'type',        // service | payment | adjustment | refund
        'amount',
        'note',
        'created_at'
    ];

    protected $casts = [
        'amount' => 'float',
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

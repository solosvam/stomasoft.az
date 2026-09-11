<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientLedger extends Model
{
    protected $table = 'patient_ledger';
    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'user_id',
        'session_id',
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

    public function session()
    {
        return $this->belongsTo(PatientServiceSession::class, 'session_id');
    }
}

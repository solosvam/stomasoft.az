<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';
    public $timestamps = false;
    protected $hidden = [];
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'service_id',
        'date',
        'hour',
        'note',
        'status'
    ];

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}

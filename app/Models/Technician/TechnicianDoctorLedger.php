<?php

namespace App\Models\Technician;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TechnicianDoctorLedger extends Model
{
    protected $table = 'technician_doctor_ledger';

    public $timestamps = false;

    protected $fillable = [
        'doctor_id',
        'user_id',
        'job_id',
        'type',
        'amount',
        'note',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function doctor()
    {
        return $this->belongsTo(TechnicianDoctor::class, 'doctor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function job()
    {
        return $this->belongsTo(TechnicianJob::class, 'job_id');
    }
}

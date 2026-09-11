<?php

namespace App\Models\Technician;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TechnicianDoctorBalance extends Model
{
    protected $table = 'technician_doctor_balances';

    public $timestamps = false;

    protected $fillable = [
        'doctor_id',
        'user_id',
        'balance',
        'updated_at',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'updated_at' => 'datetime',
    ];

    public function doctor()
    {
        return $this->belongsTo(TechnicianDoctor::class, 'doctor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

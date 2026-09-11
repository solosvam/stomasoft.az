<?php

namespace App\Models\Technician;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechnicianDoctor extends Model
{
    protected $table = 'technician_doctors';
    public $timestamps = false;
    protected $hidden = [];
    protected $fillable = [
        'user_id',
        'name',
        'surname',
        'address',
        'mobile',
    ];

    public function getFullnameAttribute()
    {
        return $this->name." ".$this->surname;
    }

    public function balances(): HasMany
    {
        return $this->hasMany(TechnicianDoctorBalance::class, 'doctor_id');
    }

    public function ledgers(): HasMany
    {
        return $this->hasMany(TechnicianDoctorLedger::class, 'doctor_id')
            ->orderByDesc('id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(TechnicianJob::class, 'doctor_id')
            ->orderByDesc('id');
    }

    public function getTotalBalanceAttribute()
    {
        return $this->balances()
            ->where('balance', '>', 0)
            ->sum('balance');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function balance()
    {
        return $this->hasOne(TechnicianDoctorBalance::class, 'doctor_id')
            ->where('user_id', auth()->id());
    }

    public function activeJobs()
    {
        return $this->hasMany(TechnicianJob::class, 'doctor_id')
            ->where('status', 'active');
    }
}

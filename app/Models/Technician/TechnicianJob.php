<?php

namespace App\Models\Technician;

use App\Models\ServiceLocation;
use App\Models\Services;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TechnicianJob extends Model
{
    protected $table = 'technician_jobs';

    protected $fillable = [
        'user_id',
        'doctor_id',
        'patient_name',
        'received_at',
        'due_at',
        'completed_at',
        'status',
        'received_type',
        'total_amount',
        'note',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'due_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(TechnicianDoctor::class, 'doctor_id');
    }

    public function items()
    {
        return $this->hasMany(TechnicianJobItem::class, 'job_id');
    }

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    public function location()
    {
        return $this->belongsTo(ServiceLocation::class, 'location_id');
    }
}

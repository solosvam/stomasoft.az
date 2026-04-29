<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientServiceSession extends Model
{
    protected $table = 'patient_service_session';
    public $timestamps = false;
    protected $hidden = [];
    protected $fillable = [
        'patient_id',
        'user_id',
        'date',
        'note',
        'total_cost',
        'status'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(PatientServiceSessionItems::class,'session_id');
    }
}

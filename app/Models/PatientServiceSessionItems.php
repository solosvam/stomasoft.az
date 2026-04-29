<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientServiceSessionItems extends Model
{
    protected $table = 'patient_service_session_items';
    public $timestamps = false;
    protected $hidden = [];
    protected $fillable = [
        'session_id',
        'service_id',
        'tooth_id',
        'note',
        'price',
        'percent',
        'price_net'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Services::class, 'service_id', 'id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(PatientServiceSession::class, 'session_id', 'id');
    }

}

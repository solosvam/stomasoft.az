<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientFiles extends Model
{
    protected $table = 'patient_files';
    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'file'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}

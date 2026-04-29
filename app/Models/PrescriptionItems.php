<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionItems extends Model
{
    protected $table = 'prescription_items';
    public $timestamps = false;
    protected $hidden = [];
    protected $fillable = [
        'prescription_id',
        'medicine_name',
        'dose',
        'usage_text',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id');
    }

}

<?php

namespace App\Models\Technician;

use App\Models\ServiceLocation;
use Illuminate\Database\Eloquent\Model;

class TechnicianJobItemLocation extends Model
{
    protected $table = 'technician_job_item_locations';

    public $timestamps = false;

    protected $fillable = [
        'job_id',
        'item_id',
        'location_id',
    ];

    public function item()
    {
        return $this->belongsTo(TechnicianJobItem::class, 'item_id');
    }

    public function location()
    {
        return $this->belongsTo(ServiceLocation::class, 'location_id');
    }

    public function job()
    {
        return $this->belongsTo(TechnicianJob::class, 'job_id');
    }
}

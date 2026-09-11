<?php

namespace App\Models\Technician;

use App\Models\ServiceLocation;
use App\Models\Services;
use Illuminate\Database\Eloquent\Model;

class TechnicianJobItem extends Model
{
    protected $table = 'technician_job_items';

    protected $fillable = [
        'job_id',
        'service_id',
        'quantity',
        'price',
        'total_price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function job()
    {
        return $this->belongsTo(TechnicianJob::class, 'job_id');
    }

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    public function locations()
    {
        return $this->hasMany(TechnicianJobItemLocation::class, 'item_id');
    }
}

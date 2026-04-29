<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerLedger extends Model
{
    protected $table = 'partner_ledger';
    public $timestamps = false;

    protected $fillable = [
        'partner_id','doctor_id','patient_id','type','amount','note','created_at'
    ];

    public function partner() { return $this->belongsTo(Partner::class,'partner_id'); }
    public function doctor()  { return $this->belongsTo(User::class,'doctor_id'); }
    public function patient() { return $this->belongsTo(Patient::class,'patient_id'); }
}

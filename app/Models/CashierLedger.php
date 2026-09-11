<?php

namespace App\Models;

use App\Models\Technician\TechnicianDoctor;
use Illuminate\Database\Eloquent\Model;

class CashierLedger extends Model
{
    protected $table = 'cashier_ledger';
    public $timestamps = false;

    protected $fillable = [
        'cashier_id','user_id','patient_id','partner_id','doctor_id',
        'type','method','amount','note','created_at'
    ];

    public const INCOME_TYPES = [
        'patient_payment',
        'adjust_in',
        'technician_payment',
    ];

    public const EXPENSE_TYPES = [
        'partner_payment',
        'doctor_payout',
        'adjust_out',
    ];

    public function technicianDoctor()
    {
        return $this->belongsTo(TechnicianDoctor::class, 'doctor_id');
    }

    public function user()  { return $this->belongsTo(User::class,'user_id'); }
    public function patient() { return $this->belongsTo(Patient::class,'patient_id'); }
    public function partner() { return $this->belongsTo(Partner::class,'partner_id'); }
}

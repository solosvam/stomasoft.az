<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashierLedger extends Model
{
    protected $table = 'cashier_ledger';
    public $timestamps = false;

    protected $fillable = [
        'cashier_id','user_id','patient_id','partner_id',
        'type','method','amount','note','created_at'
    ];

    public const INCOME_TYPES = [
        'patient_payment',
        'adjust_in',
    ];

    public const EXPENSE_TYPES = [
        'partner_payment',
        'doctor_payout',
        'adjust_out',
    ];

    public function user()  { return $this->belongsTo(User::class,'user_id'); }
    public function patient() { return $this->belongsTo(Patient::class,'patient_id'); }
    public function partner() { return $this->belongsTo(Partner::class,'partner_id'); }
}

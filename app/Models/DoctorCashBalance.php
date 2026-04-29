<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorCashBalance extends Model
{
    protected $table = 'doctor_cash_balances';
    public $timestamps = false;

    protected $primaryKey = 'doctor_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['doctor_id','balance','updated_at'];

    public function doctor()
    {
        return $this->belongsTo(User::class,'doctor_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'subscription_id', 'payment_method', 'membership_id',
        'email', 'amount', 'currency', 'period', 'unit',
        'start_at', 'end_at', 'status',
    ];

    public function membership() {
        return $this->belongsTo(Membership::class);
    }
}

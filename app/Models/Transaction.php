<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'email', 'membership_id', 'amount', 'period', 'unit',
        'started_at', 'end_at', 'type', 'status',
    ];

    public function scopeWhose($query, $email) {
        $query->where('email', $email);
    }

    public function scopeComplete($query) {
        $query->where('status', 'completed');
    }

    public function membership() {
        return $this->belongsTo(Membership::class);
    }
}

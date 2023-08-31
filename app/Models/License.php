<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'key', 'expires_on', 'active',
        'subscription_id', 'payment_method', 'note',
    ];

    public function scopeWhose($query, $email) {
        $query->where('email', $email);
    }

    public function scopeKey($query, $key) {
        $query->where('key', $key);
    }

    public function scopeActive($query) {
        $query->where('active', true);
    }

    public function scopePaymentMethod($query, $payment_method) {
        $query->where('payment_method', $payment_method);
    }

    public function label() {
        return $this->belongsTo(SyncLabel::class, 'email', 'email');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'subscription_id', 'subscription_id');
    }
}

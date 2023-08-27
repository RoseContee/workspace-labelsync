<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'key', 'expires_on', 'active', 'membership_id', 'transaction_id', 'note',
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

    public function membership() {
        return $this->belongsTo(Membership::class);
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'subject', 'message', 'read', 'replied',
    ];

    public function scopeRead($query) {
        $query->where('read', true);
    }

    public function scopeUnread($query) {
        $query->where('read', false);
    }

    public function scopeReplied($query) {
        $query->where('replied', true);
    }

    public function scopeNotReplied($query) {
        $query->where('replied', false);
    }
}

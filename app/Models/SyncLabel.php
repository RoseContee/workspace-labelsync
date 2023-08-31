<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncLabel extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'members', 'labels',
    ];

    public function scopeMember($query, $email) {
        $query->where('members', 'like', '%"'.$email.'"%');
    }

    public function license() {
        return $this->belongsTo(License::class, 'email', 'email');
    }
}

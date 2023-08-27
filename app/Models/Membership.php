<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'price', 'origin_price', 'period', 'unit',
        'supported_features', 'unsupported_features', 'description',
        'featured', 'active',
    ];

    public function scopeActive($query) {
        $query->where('active', true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'total_amount',
        'is_active'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    protected $appends = ['formatted_total_amount'];

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmountAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /**
     * Relationship with Prices
     */
    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    /**
     * Relationship with active prices
     */
    public function activePrices()
    {
        return $this->hasMany(Price::class)->active()->ordered();
    }

    /**
     * Scope active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

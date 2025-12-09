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
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $appends = ['formatted_total_amount'];

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmountAttribute()
    {
        $total = $this->activePrices->sum('amount');
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    /**
     * Get total amount without formatting
     */
    public function getTotalAmountAttribute()
    {
        return $this->activePrices->sum('amount');
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

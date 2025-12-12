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
        'required_documents',
        'perlu_verifikasi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'required_documents' => 'array'
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

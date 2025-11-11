<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'item_name', // Diubah dari 'name' menjadi 'item_name'
        'description',
        'amount',
        'order',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    protected $appends = ['formatted_amount'];

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get name attribute (alias untuk item_name untuk kompatibilitas)
     */
    public function getNameAttribute()
    {
        return $this->item_name;
    }

    /**
     * Relationship with Package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Scope active prices
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}

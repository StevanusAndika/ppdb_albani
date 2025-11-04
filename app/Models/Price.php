<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'item_name',
        'description',
        'amount',
        'order',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($price) {
            if (is_null($price->order)) {
                $maxOrder = static::where('package_id', $price->package_id)->max('order');
                $price->order = $maxOrder ? $maxOrder + 1 : 1;
            }
        });
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp.' . number_format($this->amount, 0, ',', '.');
    }
}

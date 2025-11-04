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
        'type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function prices()
    {
        return $this->hasMany(Price::class)->orderBy('order');
    }

    public function activePrices()
    {
        return $this->hasMany(Price::class)->where('is_active', true)->orderBy('order');
    }

    public function getTotalAmountAttribute()
    {
        return $this->activePrices->sum('amount');
    }

    public function getFormattedTotalAmountAttribute()
    {
        return 'Rp.' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getTypeLabelAttribute()
    {
        return $this->type === 'takhossus' ? 'Takhossus Pesantren' : 'Plus Sekolah';
    }
}

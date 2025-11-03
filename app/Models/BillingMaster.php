<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'billing_master';

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'total_amount'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_amount' => 'decimal:2'
    ];

    public function items()
    {
        return $this->hasMany(BillingItem::class, 'billing_master_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function updateTotalAmount()
    {
        $total = $this->items()->sum(\DB::raw('amount * quantity'));
        $this->update(['total_amount' => $total]);
        return $total;
    }
}

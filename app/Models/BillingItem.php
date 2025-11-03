<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingItem extends Model
{
    use HasFactory;

    protected $table = 'billing_items';

    protected $fillable = [
        'billing_master_id',
        'item_name',
        'description',
        'amount',
        'quantity'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function billingMaster()
    {
        return $this->belongsTo(BillingMaster::class);
    }

    public function getTotalAttribute()
    {
        return $this->amount * $this->quantity;
    }
}

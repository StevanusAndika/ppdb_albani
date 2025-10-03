<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'registration_id',
        'nomor_invoice',
        'jumlah_pembayaran',
        'metode_pembayaran',
        'status',
        'gateway_pembayaran',
        'id_transaksi_gateway',
        'waktu_pembayaran_sukses',
        'waktu_kadaluarsa',
    ];

    protected $casts = [
        'jumlah_pembayaran' => 'decimal:2',
        'waktu_pembayaran_sukses' => 'datetime',
        'waktu_kadaluarsa' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function paymentLogs(): HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_SUCCESS => 'green',
            self::STATUS_FAILED => 'red',
            self::STATUS_EXPIRED => 'gray',
            default => 'gray',
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_SUCCESS => 'Sukses',
            self::STATUS_FAILED => 'Gagal',
            self::STATUS_EXPIRED => 'Kadaluarsa',
            default => $this->status,
        };
    }

    public function getJumlahFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_pembayaran, 0, ',', '.');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isExpiredNow(): bool
    {
        return $this->waktu_kadaluarsa && $this->waktu_kadaluarsa->isPast();
    }

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $year = date('Y');
        $month = date('m');

        $lastInvoice = self::where('nomor_invoice', 'like', "{$prefix}/{$year}/{$month}/%")
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->nomor_invoice, -4);
            $nextNumber = $lastNumber + 1;
        }

        return "{$prefix}/{$year}/{$month}/" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}

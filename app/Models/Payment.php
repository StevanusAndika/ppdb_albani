<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_code',
        'registration_id',
        'user_id',
        'amount',
        'payment_method',
        'status',
        'xendit_id',
        'xendit_external_id',
        'xendit_response',
        'admin_notes',
        'paid_at',
        'expired_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'xendit_response' => 'array'
    ];

    protected $appends = ['formatted_amount', 'status_label', 'status_color'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_code)) {
                $payment->payment_code = 'PAY-' . date('Ymd') . '-' . strtoupper(uniqid());
            }
        });
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu',
            'waiting_payment' => 'Menunggu Pembayaran',
            'processing' => 'Sedang Diproses',
            'success' => 'Berhasil',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa',
            'lunas' => 'Lunas'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'waiting_payment' => 'info',
            'processing' => 'primary',
            'success' => 'success',
            'failed' => 'danger',
            'expired' => 'secondary',
            'lunas' => 'success'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getStatusIconAttribute()
    {
        $icons = [
            'pending' => 'fa-clock',
            'waiting_payment' => 'fa-hourglass-half',
            'processing' => 'fa-sync-alt',
            'success' => 'fa-check-circle',
            'failed' => 'fa-times-circle',
            'expired' => 'fa-calendar-times',
            'lunas' => 'fa-check-double'
        ];

        return $icons[$this->status] ?? 'fa-question-circle';
    }

    public function isPaid()
    {
        return in_array($this->status, ['success', 'lunas']);
    }

    public function isPending()
    {
        return in_array($this->status, ['pending', 'waiting_payment', 'processing']);
    }

    public function markAsPaid($method = 'manual')
    {
        $this->update([
            'status' => 'lunas',
            'paid_at' => now(),
            'admin_notes' => $method === 'manual' ? 'Pembayaran manual oleh admin' : $this->admin_notes
        ]);
    }

    public function canMakePayment()
    {
        return $this->registration &&
               $this->registration->status_pendaftaran !== 'belum_mendaftar' &&
               $this->registration->hasAllDocuments();
    }

    /**
     * Get payment status for debugging
     */
    public function getDebugInfo()
    {
        return [
            'id' => $this->id,
            'payment_code' => $this->payment_code,
            'xendit_id' => $this->xendit_id,
            'xendit_external_id' => $this->xendit_external_id,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'amount' => $this->amount,
            'paid_at' => $this->paid_at,
            'created_at' => $this->created_at,
            'registration_id' => $this->registration_id,
            'user_id' => $this->user_id
        ];
    }

    /**
     * Scope untuk payment yang perlu di-check statusnya
     */
    public function scopeNeedStatusCheck($query)
    {
        return $query->where('payment_method', 'xendit')
                    ->whereIn('status', ['pending', 'waiting_payment', 'processing'])
                    ->whereNotNull('xendit_id');
    }
}

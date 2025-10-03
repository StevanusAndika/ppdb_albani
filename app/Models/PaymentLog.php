<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    use HasFactory;

    const JENIS_REQUEST = 'request';
    const JENIS_CALLBACK = 'callback';
    const JENIS_NOTIFICATION = 'notification';

    protected $fillable = [
        'payment_id',
        'jenis_log',
        'data_payload',
        'status_respons',
        'waktu_pembuatan_log',
    ];

    protected $casts = [
        'data_payload' => 'array',
        'waktu_pembuatan_log' => 'datetime',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function getJenisLogTextAttribute(): string
    {
        return match($this->jenis_log) {
            self::JENIS_REQUEST => 'Request',
            self::JENIS_CALLBACK => 'Callback',
            self::JENIS_NOTIFICATION => 'Notification',
            default => $this->jenis_log,
        };
    }

    public function getPayloadPreviewAttribute(): string
    {
        $payload = json_encode($this->data_payload, JSON_PRETTY_PRINT);
        return substr($payload, 0, 200) . (strlen($payload) > 200 ? '...' : '');
    }
}

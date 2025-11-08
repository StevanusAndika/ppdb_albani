<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id', // sekarang bisa null
        'title',
        'message',
        'status',
        'recipients',
        'sent_at'
    ];

    protected $casts = [
        'recipients' => 'array',
        'sent_at' => 'datetime'
    ];

    // Relationship bisa null
    public function registration()
    {
        return $this->belongsTo(Registration::class)->withDefault();
    }

    // Scope untuk announcement individual
    public function scopeIndividual($query)
    {
        return $query->whereNotNull('registration_id');
    }

    // Scope untuk announcement bulk
    public function scopeBulk($query)
    {
        return $query->whereNull('registration_id');
    }

    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    public function markAsFailed()
    {
        $this->update(['status' => 'failed']);
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    // Cek apakah ini announcement individual
    public function isIndividual(): bool
    {
        return !is_null($this->registration_id);
    }

    // Cek apakah ini announcement bulk - INI YANG DIPANGGIL DI VIEW
    public function isBulk(): bool
    {
        return is_null($this->registration_id);
    }

    // Get recipient names untuk display
    public function getRecipientNamesAttribute()
    {
        if ($this->isIndividual() && $this->registration) {
            return [$this->registration->nama_lengkap];
        }

        // Untuk bulk, kita tidak punya nama spesifik
        return ['Multiple Recipients'];
    }

    // Get status label untuk display
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu',
            'sent' => 'Terkirim',
            'failed' => 'Gagal'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
 * Scope untuk mencari announcement berdasarkan nomor telepon di recipients
 */
public function scopeWherePhoneSent($query, $phoneNumber)
{
    return $query->where('status', 'sent')
                ->whereJsonContains('recipients', $phoneNumber);
}

/**
 * Cek apakah nomor telepon sudah pernah dikirim
 */
public static function isPhoneAlreadySent($phoneNumber): bool
{
    return static::wherePhoneSent($phoneNumber)->exists();
}

    // Get status color untuk display
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'sent' => 'green',
            'failed' => 'red'
        ];

        return $colors[$this->status] ?? 'gray';
    }
}

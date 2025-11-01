<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp',
        'phone_number',
        'expires_at',
        'is_used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean'
    ];

    /**
     * Scope untuk OTP yang masih valid
     */
    public function scopeValid($query)
    {
        return $query->where('is_used', false)
                    ->where('expires_at', '>', now());
    }

    /**
     * Cek apakah OTP sudah kadaluarsa
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Tandai OTP sebagai sudah digunakan
     */
    public function markAsUsed(): void
    {
        $this->update(['is_used' => true]);
    }

    /**
     * Hapus OTP yang sudah kadaluarsa
     */
    public static function cleanupExpired(): void
    {
        static::where('expires_at', '<', now()->subHours(2))->delete();
    }
}

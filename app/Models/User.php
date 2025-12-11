<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'is_active',
        'provider_id',
        'provider_name',
        'login_attempts',
        'locked_until',
        'last_login_attempt',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'locked_until' => 'datetime',
            'last_login_attempt' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCalonSantri(): bool
    {
        return $this->role === 'calon_santri';
    }

    public function isSocialiteUser(): bool
    {
        return !is_null($this->provider_id) && !is_null($this->provider_name);
    }

    public function hasManualPassword(): bool
    {
        return !is_null($this->password) && $this->password !== '';
    }

    public function canLoginManually(): bool
    {
        return $this->hasManualPassword();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeNotLocked($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('locked_until')
              ->orWhere('locked_until', '<', Carbon::now());
        });
    }

    public function activate(): void
    {
        $this->update([
            'is_active' => true,
            'login_attempts' => 0,
            'locked_until' => null,
            'last_login_attempt' => null,
        ]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    // Method untuk lock account - 5 menit
    public function lockAccount(int $minutes = 5): void
    {
        $this->update([
            'login_attempts' => 3,
            'locked_until' => Carbon::now()->addMinutes($minutes),
        ]);
    }

    // Method untuk unlock account
    public function unlockAccount(): void
    {
        $this->update([
            'login_attempts' => 0,
            'locked_until' => null,
            'is_active' => true,
            'last_login_attempt' => null,
        ]);
    }

    // Method untuk increment login attempts
    public function incrementLoginAttempts(): void
    {
        $newAttempts = $this->login_attempts + 1;

        $this->update([
            'login_attempts' => $newAttempts,
            'last_login_attempt' => Carbon::now(),
        ]);

        // Jika sudah 3 kali attempt, lock account selama 5 menit
        if ($newAttempts >= 3) {
            $this->lockAccount(5);
        }
    }

    // Method untuk reset login attempts
    public function resetLoginAttempts(): void
    {
        $this->update([
            'login_attempts' => 0,
            'locked_until' => null,
            'last_login_attempt' => null,
        ]);
    }

    // Method untuk cek apakah account terkunci
    public function isLocked(): bool
    {
        return !is_null($this->locked_until) && $this->locked_until > Carbon::now();
    }

    // Method untuk cek apakah account harus di-unlock
    public function shouldUnlock(): bool
    {
        return !is_null($this->locked_until) && $this->locked_until <= Carbon::now();
    }

    // Method untuk get waktu lock yang tersisa
    public function getLockRemainingMinutes(): int
    {
        if (!$this->locked_until) {
            return 0;
        }

        $remaining = Carbon::now()->diffInMinutes($this->locked_until, false);
        return max(0, $remaining);
    }

    // Method untuk get sisa percobaan login
    public function getRemainingAttempts(): int
    {
        return max(0, 3 - $this->login_attempts);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function userDocuments()
    {
        return $this->hasMany(UserDocument::class);
    }

    // Method baru untuk mendapatkan nomor telepon yang diformat
    public function getFormattedPhoneNumber(): string
    {
        $phone = $this->phone_number;

        if (!$phone) {
            return '';
        }

        // Hapus karakter non-digit
        $phone = preg_replace('/\D/', '', $phone);

        // Jika diawali dengan 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Jika tidak diawali dengan 62, tambahkan
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}

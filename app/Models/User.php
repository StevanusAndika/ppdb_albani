<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        // User bisa login manual jika:
        // 1. Memiliki password manual, ATAU
        // 2. Socialite user yang sudah set password melalui reset password
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

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}

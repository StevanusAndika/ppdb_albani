<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope untuk pengguna aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk pengguna berdasarkan role
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk pengguna berdasarkan provider
     */
    public function scopeProvider($query, $provider)
    {
        return $query->where('provider_name', $provider);
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah calon santri
     */
    public function isCalonSantri(): bool
    {
        return $this->role === 'calon_santri';
    }

    /**
     * Cek apakah user login via socialite
     */
    public function isSocialiteUser(): bool
    {
        return !is_null($this->provider_id) && !is_null($this->provider_name);
    }

    /**
     * Aktifkan pengguna
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Nonaktifkan pengguna
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Set role admin
     */
    public function setAdmin(): void
    {
        $this->update(['role' => 'admin']);
    }

    /**
     * Set role calon santri
     */
    public function setCalonSantri(): void
    {
        $this->update(['role' => 'calon_santri']);
    }

    // Tambahkan method ini di User model
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}

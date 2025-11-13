<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quota extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_akademik',
        'kuota',
        'terpakai',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function getSisaAttribute()
    {
        return $this->kuota - $this->terpakai;
    }

    public function getPersentaseTerpakaiAttribute()
    {
        if ($this->kuota == 0) return 0;
        return ($this->terpakai / $this->kuota) * 100;
    }

    public function isAvailable()
    {
        return $this->sisa > 0 && $this->is_active;
    }

    public function incrementUsed()
    {
        $this->increment('terpakai');
    }

    public function decrementUsed()
    {
        if ($this->terpakai > 0) {
            $this->decrement('terpakai');
        }
    }

    public static function getActiveQuota()
    {
        return self::where('is_active', true)->first();
    }

    public static function checkAvailability()
    {
        $quota = self::getActiveQuota();
        return $quota ? $quota->isAvailable() : false;
    }

    public static function reserveQuota()
    {
        $quota = self::getActiveQuota();
        if ($quota && $quota->isAvailable()) {
            $quota->incrementUsed();
            return true;
        }
        return false;
    }

    public static function releaseQuota()
    {
        $quota = self::getActiveQuota();
        if ($quota) {
            $quota->decrementUsed();
            return true;
        }
        return false;
    }
}

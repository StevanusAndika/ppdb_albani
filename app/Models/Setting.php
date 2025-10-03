<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'kunci_pengaturan',
        'nilai_pengaturan',
        'deskripsi_pengaturan',
        'diperbarui_oleh',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diperbarui_oleh');
    }

    public static function getValue($key, $default = null)
    {
        $setting = self::where('kunci_pengaturan', $key)->first();
        return $setting ? $setting->nilai_pengaturan : $default;
    }

    public static function setValue($key, $value, $description = null, $userId = null)
    {
        return self::updateOrCreate(
            ['kunci_pengaturan' => $key],
            [
                'nilai_pengaturan' => $value,
                'deskripsi_pengaturan' => $description,
                'diperbarui_oleh' => $userId,
            ]
        );
    }

    public static function getBool($key, $default = false): bool
    {
        $value = self::getValue($key, $default);
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function getInt($key, $default = 0): int
    {
        $value = self::getValue($key, $default);
        return (int) $value;
    }

    public static function getArray($key, $default = []): array
    {
        $value = self::getValue($key);
        if ($value && is_string($value)) {
            return json_decode($value, true) ?? $default;
        }
        return $default;
    }

    public static function getSystemSettings(): array
    {
        return [
            'tahun_ppdb' => self::getValue('tahun_ppdb', date('Y')),
            'status_ppdb' => self::getBool('status_ppdb', true),
            'tanggal_mulai' => self::getValue('tanggal_mulai'),
            'tanggal_selesai' => self::getValue('tanggal_selesai'),
            'biaya_pendaftaran' => self::getInt('biaya_pendaftaran', 0),
            'gateway_pembayaran' => self::getValue('gateway_pembayaran', 'xendit'),
            'email_admin' => self::getValue('email_admin'),
            'telepon_admin' => self::getValue('telepon_admin'),
        ];
    }
}

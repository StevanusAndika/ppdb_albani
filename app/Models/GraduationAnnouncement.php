<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GraduationAnnouncement extends Model
{
    use HasFactory;

    protected $table = 'graduation_announcements';

    protected $fillable = [
        'registration_id',
        'announcement_type',
        'title',
        'message',
        'status',
        'recipients',
        'recipient_count',
        'sent_at'
    ];

    protected $casts = [
        'recipients' => 'array',
        'sent_at' => 'datetime'
    ];

    protected $appends = [
        'status_label',
        'status_color',
        'recipient_info',
        'type_label'
    ];

    // Relationship (nullable karena bisa summary)
    public function registration()
    {
        return $this->belongsTo(Registration::class)->withDefault();
    }

    // Scopes
    public function scopeIndividual($query)
    {
        return $query->where('announcement_type', 'individual');
    }

    public function scopeBulk($query)
    {
        return $query->where('announcement_type', 'bulk');
    }

    public function scopeSummary($query)
    {
        return $query->where('announcement_type', 'summary');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Helper Methods
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

    public function isIndividual(): bool
    {
        return $this->announcement_type === 'individual';
    }

    public function isBulk(): bool
    {
        return $this->announcement_type === 'bulk';
    }

    public function isSummary(): bool
    {
        return $this->announcement_type === 'summary';
    }

    // Check if phone has already received graduation announcement
    public static function isPhoneAlreadySent($phoneNumber): bool
    {
        return static::where('status', 'sent')
                    ->whereJsonContains('recipients', $phoneNumber)
                    ->exists();
    }

    // Check if registration has already received graduation announcement
    public static function hasRegistrationBeenSent($registrationId): bool
    {
        return static::where('registration_id', $registrationId)
                    ->where('status', 'sent')
                    ->where('announcement_type', 'individual')
                    ->exists();
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'Menunggu',
            'sent' => 'Terkirim',
            'failed' => 'Gagal'
        ][$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'warning',
            'sent' => 'success',
            'failed' => 'danger'
        ][$this->status] ?? 'secondary';
    }

    public function getTypeLabelAttribute()
    {
        return [
            'individual' => 'Individual',
            'bulk' => 'Bulk',
            'summary' => 'Summary'
        ][$this->announcement_type] ?? $this->announcement_type;
    }

    public function getRecipientInfoAttribute()
    {
        if (!$this->registration && $this->announcement_type !== 'summary') {
            return null;
        }

        if ($this->announcement_type === 'summary') {
            return [
                'type' => 'summary',
                'count' => $this->recipient_count,
                'recipients' => $this->recipients
            ];
        }

        return [
            'name' => $this->registration->nama_lengkap ?? 'Unknown',
            'phone' => $this->registration->user->phone_number ?? null,
            'program' => $this->registration->program_pendidikan_label ?? null,
            'registration_id' => $this->registration->id_pendaftaran ?? null
        ];
    }

    // Format message for display
    public function getShortMessageAttribute()
    {
        if (strlen($this->message) > 100) {
            return substr($this->message, 0, 100) . '...';
        }
        return $this->message;
    }
}

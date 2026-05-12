<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteManagement extends Model
{
    use HasFactory;

    protected $table = 'website_management';

    protected $fillable = [
        'is_locked',
        'lock_message',
        'update_message_expiry_date',
        'update_message_siswa',
        'update_message_guru',
        'update_message_kepala_sekolah',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'update_message_expiry_date' => 'date',
    ];

    // Get the singleton record (only one record should exist)
    public static function getSettings(): self
    {
        return self::firstOrCreate([], [
            'is_locked' => false,
            'lock_message' => null,
            'update_message_expiry_date' => null,
            'update_message_siswa' => null,
            'update_message_guru' => null,
            'update_message_kepala_sekolah' => null,
        ]);
    }

    // Check if website is currently locked
    public static function isWebsiteLocked(): bool
    {
        return self::getSettings()->is_locked;
    }

    // Get lock message
    public static function getLockMessage(): ?string
    {
        return self::getSettings()->lock_message;
    }

    // Check if update messages are still valid (not expired)
    public static function hasValidUpdateMessages(): bool
    {
        $settings = self::getSettings();
        
        if (!$settings->update_message_expiry_date) {
            return false;
        }
        
        return now()->startOfDay()->lte($settings->update_message_expiry_date);
    }

    // Get update message for specific role
    public static function getUpdateMessage(string $role): ?string
    {
        if (!self::hasValidUpdateMessages()) {
            return null;
        }

        $settings = self::getSettings();
        
        return match($role) {
            'siswa' => $settings->update_message_siswa,
            'guru' => $settings->update_message_guru,
            'kepala_sekolah' => $settings->update_message_kepala_sekolah,
            default => null,
        };
    }
}

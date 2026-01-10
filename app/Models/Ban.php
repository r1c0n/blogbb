<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ban extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'reason',
    ];

    /**
     * Check if an IP address is banned
     */
    public static function isBanned(string $ip): bool
    {
        return static::where('ip', $ip)->exists();
    }

    /**
     * Ban an IP address
     */
    public static function banIp(string $ip, ?string $reason = null): self
    {
        return static::create([
            'ip' => $ip,
            'reason' => $reason,
        ]);
    }

    /**
     * Unban an IP address
     */
    public static function unbanIp(string $ip): bool
    {
        return static::where('ip', $ip)->delete() > 0;
    }
}

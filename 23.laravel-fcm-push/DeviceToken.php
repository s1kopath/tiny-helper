<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceToken extends Model
{
    use HasFactory;
    
    public function storeToken($token, $deviceType = 'web')
    {
        self::where('user_id', auth()->id())
            ->where('created_at', '<', now()->subMonth())
            ->delete();

        if (!self::whereUserId(auth()->id())->whereToken($token)->exists()) {
            self::create([
                'user_id' => Auth::id(),
                'token' => $token,
                'device_type' => $deviceType,
                'device_name' => request()->userAgent(),
            ]);
        }

        return true;
    }

    protected $fillable = [
        'user_id',
        'token',
        'device_type',
        'device_name',
        'is_active',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    /**
     * Get the user that owns the device token
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only active tokens
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Mark token as used
     */
    public function markAsUsed()
    {
        $this->update(['last_used_at' => now()]);
    }
}

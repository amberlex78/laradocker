<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'session_id',
    'logged_in_at',
    'logged_out_at',
    'logout_reason',
    'ip_address',
    'device_type',
    'device_model',
    'operating_system',
    'browser',
    'browser_version',
])]
class LoginHistory extends Model
{
    public $timestamps = false;

    /**
     * Get the user who made this login.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'logged_in_at' => 'datetime',
            'logged_out_at' => 'datetime',
        ];
    }
}

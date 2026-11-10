<?php

namespace App\Models;

use App\Enums\LoginSessionEndReason;
use Database\Factories\LoginSessionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property int $user_id
 * @property string|null $guard
 * @property string|null $session_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $client
 * @property string|null $operating_system
 * @property string|null $device
 * @property Carbon $logged_in_at
 * @property Carbon $last_seen_at
 * @property Carbon|null $logged_out_at
 * @property LoginSessionEndReason|null $end_reason
 */
#[Fillable([
    'user_id',
    'guard',
    'session_id',
    'ip_address',
    'user_agent',
    'client',
    'operating_system',
    'device',
    'logged_in_at',
    'last_seen_at',
    'logged_out_at',
    'end_reason',
])]
class LoginSession extends Model
{
    /** @use HasFactory<LoginSessionFactory> */
    use HasFactory, HasUlids;

    public $timestamps = false;

    /**
     * The key stored in the HTTP session to identify the current journal entry.
     */
    public const SESSION_KEY = 'login_session_id';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'logged_in_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'logged_out_at' => 'datetime',
            'end_reason' => LoginSessionEndReason::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sessions that were not ended and have been seen within the session lifetime.
     *
     * @param  Builder<LoginSession>  $query
     * @return Builder<LoginSession>
     */
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query
            ->whereNull('logged_out_at')
            ->where('last_seen_at', '>=', now()->subMinutes((int) config('session.lifetime')));
    }
}

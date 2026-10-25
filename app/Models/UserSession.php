<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property int|null $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string $payload
 * @property int $last_activity
 */
#[Fillable(['id', 'user_id', 'ip_address', 'user_agent', 'payload', 'last_activity'])]
class UserSession extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable((string) config('session.table'));
        $this->setConnection(config('session.connection') === null
            ? null
            : (string) config('session.connection'));
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  Builder<UserSession>  $query
     * @return Builder<UserSession>
     */
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where(
            'last_activity',
            '>=',
            now()->subMinutes((int) config('session.lifetime'))->timestamp,
        );
    }

    public function lastActivityAt(): Carbon
    {
        return Carbon::createFromTimestamp($this->last_activity);
    }
}

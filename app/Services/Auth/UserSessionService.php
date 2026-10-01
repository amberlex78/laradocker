<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class UserSessionService
{
    public function __construct(
        private readonly DeviceDetectionService $deviceDetection,
    ) {}

    /**
     * Get the user's active sessions with the current session first.
     *
     * The current session is queried separately so this ordering stays explicit
     * without embedding the rule in a raw SQL expression.
     *
     * @return Collection<int, array{is_current: bool, device_type: string, device_model: ?string, browser: ?string, browser_version: ?string, ip_address: ?string, last_activity: Carbon}>
     */
    public function activeFor(User $user, string $currentSessionId): Collection
    {
        $now = now();
        $activeSince = $now->copy()->subMinutes((int) config('session.lifetime'))->timestamp;
        $activeSessionsQuery = $this->sessionsForUser($user)
            ->select(['id', 'ip_address', 'user_agent', 'last_activity'])
            ->where('last_activity', '>=', $activeSince);

        $currentSession = (clone $activeSessionsQuery)
            ->where('id', $currentSessionId)
            ->first();

        $otherSessions = (clone $activeSessionsQuery)
            ->where('id', '<>', $currentSessionId)
            ->orderByDesc('last_activity')
            ->orderBy('id')
            ->limit($currentSession ? 9 : 10)
            ->get();

        return collect($currentSession ? [$currentSession] : [])
            ->merge($otherSessions)
            ->map(function (object $session) use ($currentSessionId, $now): array {
                $detected = $this->deviceDetection->fromUserAgent($session->user_agent);

                return [
                    'is_current' => hash_equals($currentSessionId, (string) $session->id),
                    'device_type' => $detected['device_type'],
                    'device_model' => $detected['device_model'],
                    'browser' => $detected['browser'],
                    'browser_version' => $detected['browser_version'],
                    'ip_address' => $session->ip_address,
                    'last_activity' => $now->copy()->setTimestamp((int) $session->last_activity),
                ];
            });
    }

    /**
     * Remove every database session for the user except the current session.
     *
     * The user scope is part of the query so another user's session can never
     * be terminated by this operation.
     */
    public function terminateOthers(User $user, string $currentSessionId): void
    {
        $this->sessionsForUser($user)
            ->where('id', '<>', $currentSessionId)
            ->delete();
    }

    private function sessionsForUser(User $user): Builder
    {
        return DB::table('sessions')
            ->where('user_id', $user->getKey());
    }
}

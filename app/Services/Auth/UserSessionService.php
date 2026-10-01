<?php

namespace App\Services\Auth;

use App\Enums\LogoutReason;
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
     * @return Collection<int, array{session_id: string, is_current: bool, device_type: string, device_model: ?string, operating_system: ?string, browser: ?string, browser_version: ?string, ip_address: ?string, last_activity: Carbon}>
     */
    public function activeFor(User $user, string $currentSessionId): Collection
    {
        $now = now();
        $activeSessionsQuery = $this->activeSessionsQuery($user, $now)
            ->select(['id', 'ip_address', 'user_agent', 'last_activity'])
            ->orderByDesc('last_activity')
            ->orderBy('id');

        $currentSession = (clone $activeSessionsQuery)
            ->where('id', $currentSessionId)
            ->first();

        $otherSessions = (clone $activeSessionsQuery)
            ->where('id', '<>', $currentSessionId)
            ->limit($currentSession ? 9 : 10)
            ->get();

        $sessions = collect($currentSession ? [$currentSession] : [])
            ->merge($otherSessions);
        $loginHistories = $user->loginHistories()
            ->whereIn('session_id', $sessions->pluck('id'))
            ->get()
            ->keyBy('session_id');

        return $sessions->map(function (object $session) use ($currentSessionId, $now, $loginHistories): array {
            $loginHistory = $loginHistories->get((string) $session->id);
            $detected = $loginHistory
                ? [
                    'device_type' => $loginHistory->device_type ?: 'unknown',
                    'device_model' => $loginHistory->device_model,
                    'operating_system' => $loginHistory->operating_system,
                    'browser' => $loginHistory->browser,
                    'browser_version' => $loginHistory->browser_version,
                ]
                : $this->deviceDetection->fromUserAgent($session->user_agent);

            return [
                'session_id' => (string) $session->id,
                'is_current' => hash_equals($currentSessionId, (string) $session->id),
                'device_type' => $detected['device_type'],
                'device_model' => $detected['device_model'],
                'operating_system' => $detected['operating_system'],
                'browser' => $detected['browser'],
                'browser_version' => $detected['browser_version'],
                'ip_address' => $session->ip_address,
                'last_activity' => $now->copy()->setTimestamp((int) $session->last_activity),
            ];
        });
    }

    /**
     * Find which session IDs from the visible history page are still active.
     *
     * @return Collection<int, string>
     */
    public function activeSessionIdsFor(User $user, Collection $sessionIds): Collection
    {
        if ($sessionIds->isEmpty()) {
            return collect();
        }

        return $this->activeSessionsQuery($user, now())
            ->whereIn('id', $sessionIds)
            ->orderBy('id')
            ->pluck('id')
            ->map(static fn (mixed $sessionId): string => (string) $sessionId);
    }

    /**
     * Remove every database session for the user except the current session.
     *
     * The user scope is part of the query so another user's session can never
     * be terminated by this operation.
     */
    public function terminateOthers(
        User $user,
        string $currentSessionId,
        LogoutReason $reason = LogoutReason::Terminated,
    ): void {
        DB::transaction(function () use ($user, $currentSessionId, $reason): void {
            $otherSessionIds = $this->sessionsForUser($user)
                ->where('id', '<>', $currentSessionId)
                ->pluck('id');

            if ($otherSessionIds->isEmpty()) {
                return;
            }

            $user->loginHistories()
                ->whereIn('session_id', $otherSessionIds)
                ->whereNull('logged_out_at')
                ->update([
                    'logged_out_at' => now(),
                    'logout_reason' => $reason->value,
                ]);

            $this->sessionsForUser($user)
                ->whereIn('id', $otherSessionIds)
                ->delete();
        });
    }

    /**
     * Remove every database session for the user.
     */
    public function terminateAll(User $user, LogoutReason $reason): void
    {
        DB::transaction(function () use ($user, $reason): void {
            $user->loginHistories()
                ->whereNull('logged_out_at')
                ->update([
                    'logged_out_at' => now(),
                    'logout_reason' => $reason->value,
                ]);

            $this->sessionsForUser($user)->delete();
        });
    }

    private function sessionsForUser(User $user): Builder
    {
        return DB::connection(config('session.connection'))
            ->table(config('session.table', 'sessions'))
            ->where('user_id', $user->getKey());
    }

    private function activeSessionsQuery(User $user, Carbon $now): Builder
    {
        return $this->sessionsForUser($user)
            ->where(
                'last_activity',
                '>=',
                $now->copy()->subMinutes((int) config('session.lifetime'))->timestamp,
            );
    }
}

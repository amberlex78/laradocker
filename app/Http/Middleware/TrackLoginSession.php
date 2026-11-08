<?php

namespace App\Http\Middleware;

use App\Actions\Auth\StartLoginSession;
use App\Models\LoginSession;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps the login journal in sync with the HTTP session.
 *
 * Writes are throttled so the database is not hit on every request.
 */
class TrackLoginSession
{
    private const TOUCH_INTERVAL_MINUTES = 1;

    public function __construct(private StartLoginSession $startLoginSession) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = Filament::auth()->user();

        if ($user === null) {
            return $next($request);
        }

        $loginSession = LoginSession::query()
            ->whereBelongsTo($user)
            ->whereKey(session()->get(LoginSession::SESSION_KEY))
            ->first();

        if ($loginSession === null) {
            $this->startLoginSession->handle($user, Filament::getAuthGuard(), $request);

            return $next($request);
        }

        if ($loginSession->last_seen_at->lte(now()->subMinutes(self::TOUCH_INTERVAL_MINUTES))) {
            $loginSession->forceFill([
                'session_id' => session()->getId(),
                'last_seen_at' => now(),
            ])->save();
        }

        return $next($request);
    }
}

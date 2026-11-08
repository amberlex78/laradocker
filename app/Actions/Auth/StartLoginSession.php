<?php

namespace App\Actions\Auth;

use App\Models\LoginSession;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class StartLoginSession
{
    public function handle(Authenticatable $user, ?string $guard, Request $request): LoginSession
    {
        $loginSession = LoginSession::query()->create([
            'user_id' => $user->getAuthIdentifier(),
            'guard' => $guard,
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'logged_in_at' => now(),
            'last_seen_at' => now(),
        ]);

        session()->put(LoginSession::SESSION_KEY, $loginSession->getKey());

        return $loginSession;
    }
}

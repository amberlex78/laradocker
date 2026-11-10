<?php

namespace App\Actions\Auth;

use App\Models\LoginSession;
use App\Services\DeviceDetectionService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class StartLoginSession
{
    public function __construct(private DeviceDetectionService $deviceDetectionService) {}

    public function handle(Authenticatable $user, ?string $guard, Request $request): LoginSession
    {
        $device = $this->deviceDetectionService->detect(
            $request->userAgent(),
            $request->server->all(),
        );

        $loginSession = LoginSession::query()->create([
            'user_id' => $user->getAuthIdentifier(),
            'guard' => $guard,
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'client' => $device['client'],
            'operating_system' => $device['operatingSystem'],
            'device' => $device['device'],
            'logged_in_at' => now(),
            'last_seen_at' => now(),
        ]);

        session()->put(LoginSession::SESSION_KEY, $loginSession->getKey());

        return $loginSession;
    }
}

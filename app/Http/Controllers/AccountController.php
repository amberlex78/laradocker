<?php

namespace App\Http\Controllers;

use App\Actions\Auth\ResolveDeviceInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __invoke(Request $request, ResolveDeviceInformation $deviceInformation): View
    {
        /** @var User $user */
        $user = $request->user();
        $currentSessionId = $request->session()->getId();
        $activeSince = now()->subMinutes((int) config('session.lifetime'))->timestamp;

        $activeSessions = DB::table('sessions')
            ->select(['id', 'ip_address', 'user_agent', 'last_activity'])
            ->where('user_id', $user->getKey())
            ->where('last_activity', '>=', $activeSince)
            ->orderByRaw('CASE WHEN id = ? THEN 0 ELSE 1 END', [$currentSessionId])
            ->orderByDesc('last_activity')
            ->orderBy('id')
            ->limit(10)
            ->get()
            ->map(function (object $session) use ($currentSessionId, $deviceInformation): array {
                $detected = $deviceInformation->fromUserAgent($session->user_agent);

                return [
                    'is_current' => hash_equals($currentSessionId, (string) $session->id),
                    'device_type' => $detected['device_type'],
                    'device_model' => $detected['device_model'],
                    'browser' => $detected['browser'],
                    'browser_version' => $detected['browser_version'],
                    'ip_address' => $session->ip_address,
                    'last_activity' => now()->setTimestamp((int) $session->last_activity),
                ];
            });

        return view('account.index', [
            'user' => $user,
            'activeSessions' => $activeSessions,
            'loginHistories' => $user->loginHistories()
                ->orderByDesc('logged_in_at')
                ->paginate(10),
        ]);
    }
}

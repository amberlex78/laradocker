<?php

namespace App\Http\Controllers;

use App\Actions\Auth\TerminateOtherSessions;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountSessionController extends Controller
{
    public function __invoke(Request $request, TerminateOtherSessions $terminateOtherSessions): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $terminateOtherSessions->handle($user, $request->session()->getId());

        return to_route('account')->with('status', 'active-sessions-terminated');
    }
}

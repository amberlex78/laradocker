<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __invoke(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        return view('account.index', [
            'user' => $user,
            'loginHistories' => $user->loginHistories()
                ->orderByDesc('logged_in_at')
                ->paginate(10),
        ]);
    }
}

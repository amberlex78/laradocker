<?php

namespace App\Http\Responses;

use App\Enums\UserRole;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;

class FilamentLoginResponse implements LoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        $user = Filament::auth()->user();

        $url = match ($user->role) {
            UserRole::Admin, UserRole::Developer => Filament::getPanel('admin')->getUrl(),
            UserRole::Operator => Filament::getPanel('operator')->getUrl(),
            UserRole::User => route('home'),
        };

        return redirect()->to($url);
    }
}

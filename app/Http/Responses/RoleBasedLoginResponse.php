<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Http\RedirectResponse;

class RoleBasedLoginResponse implements LoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        $user = Filament::auth()->user();

        if ($user?->hasRole('admin')) {
            return redirect()->to('/admin');
        }

        if ($user?->hasRole('wali kelas')) {
            return redirect()->to('/teacher');
        }

        Filament::auth()->logout();

        return redirect()->to('/admin/login');
    }
}

<?php

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        if (! Filament::auth()->attempt(
            $this->getCredentialsFromFormData($data),
            $data['remember'] ?? false
        )) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if (! $user) {
            $this->throwFailureValidationException();
        }

        session()->regenerate();

        if ($user->hasRole('admin')) {
            return redirect()->to('/admin');
        }

        if ($user->hasRole('wali kelas')) {
            return redirect()->to('/teacher');
        }

        Filament::auth()->logout();

        $this->throwFailureValidationException();
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}

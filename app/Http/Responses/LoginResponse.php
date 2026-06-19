<?php

namespace App\Http\Responses;

use App\Enums\UserRole;
use App\Filament\Pages\RWDashboard;
use Filament\Auth\Http\Responses\LoginResponse as BaseLoginResponse;
use Filament\Pages\Dashboard;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends BaseLoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $role = auth()->user()->role;

        if ($role === UserRole::RW) {
            return redirect()->to(RWDashboard::getUrl(panel: 'rw'));
        } elseif ($role === UserRole::RT) {
            return redirect()->to(Dashboard::getUrl(panel: 'rt'));
        } else {
            return redirect()->to(Dashboard::getUrl(panel: 'warga'));
        }
    }
}

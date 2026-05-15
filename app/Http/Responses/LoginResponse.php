<?php

namespace App\Http\Responses;

use Filament\Pages\Dashboard;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Filament\Auth\Http\Responses\LoginResponse as BaseLoginResponse;

class LoginResponse extends BaseLoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $role = auth()->user()->role;

        if ($role === "RW") {
            return redirect()->to(Dashboard::getUrl(panel: "rw"));
        } elseif ($role === "RT") {
            return redirect()->to(Dashboard::getUrl(panel: "rt"));
        } else {
            return redirect()->to(Dashboard::getUrl(panel: "warga"));
        }
    }
}

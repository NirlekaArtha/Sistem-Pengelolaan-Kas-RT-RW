<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToProperPanel
{
    private function getPanelIdForRole(?string $role): ?string
    {
        return match ($role) {
            UserRole::RW->value => UserRole::RW->getPanelId(),
            UserRole::RT->value => UserRole::RT->getPanelId(),
            UserRole::WARGA->value => UserRole::WARGA->getPanelId(),
            default => null,
        };
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return $next($request);
        }

        $userPanel = $this->getPanelIdForRole(auth()->user()->role?->value);

        if ($userPanel === null) {
            return $next($request);
        }

        // Cek apakah request path sesuai dengan panel user
        $requestPath = $request->path(); // e.g. "rw/something" atau "rt/dashboard"

        if (! str_starts_with($requestPath, $userPanel)) {
            // User akses panel yang bukan miliknya → redirect ke panel yang benar
            $correctUrl = filament()->getPanel($userPanel)->getUrl();

            return redirect()->to($correctUrl);
        }

        return $next($request);
    }
}

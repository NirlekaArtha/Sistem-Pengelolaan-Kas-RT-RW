<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToProperPanel
{
    // Map role → path panel
    private array $rolePanelMap = [
        "RW" => "rw",
        "RT" => "rt",
        "Warga" => "warga",
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $role = auth()->user()->role;
        $userPanel = $this->rolePanelMap[$role] ?? null;

        if (!$userPanel) {
            return $next($request);
        }

        // Cek apakah request path sesuai dengan panel user
        $requestPath = $request->path(); // e.g. "rw/something" atau "rt/dashboard"

        if (!str_starts_with($requestPath, $userPanel)) {
            // User akses panel yang bukan miliknya → redirect ke panel yang benar
            $correctUrl = filament()->getPanel($userPanel)->getUrl();
            return redirect()->to($correctUrl);
        }

        return $next($request);
    }
}

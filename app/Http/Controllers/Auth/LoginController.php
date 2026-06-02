<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view("auth.login");
    }

    public function login(Request $request)
    {
        $request->validate([
            "login" => "required|string",
            "password" => "required|string",
        ]);

        // Cek apakah input berupa email atau name biasa
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? "email"
            : "name";

        $credentials = [
            $loginType => $request->login,
            "password" => $request->password,
        ];

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return $this->redirectUser(Auth::user());
        }

        return back()
            ->withErrors([
                "login" =>
                    "Kredensial yang Anda masukkan tidak cocok dengan data kami.",
            ])
            ->onlyInput("login");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("login");
    }

    // Fungsi pembantu untuk mengarahkan user berdasarkan role ke panelnya masing-masing
    protected function redirectUser($user)
    {
        // Sesuaikan nama kolom role di database Anda (misal: $user->role)
        return match ($user->role) {
            "RW" => redirect("/rw"),
            "RT" => redirect("/rt"),
            "Warga" => redirect("/warga"),
            default => redirect("/"),
        };
    }
}

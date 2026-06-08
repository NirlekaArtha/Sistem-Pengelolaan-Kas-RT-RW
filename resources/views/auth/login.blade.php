<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>

    <script>
        (() => {
            const theme = localStorage.getItem("theme-preference") || "system";
            const isDark =
                theme === "dark" ||
                (theme === "system" &&
                    window.matchMedia("(prefers-color-scheme: dark)").matches);

            document.documentElement.classList.toggle("dark", isDark);
            document.documentElement.dataset.themePreference = theme;
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[linear-gradient(180deg,#eff6ff_0%,#dbeafe_40%,#f8fafc_100%)] text-slate-900 transition-colors duration-300 dark:bg-[linear-gradient(180deg,#020617_0%,#0f172a_50%,#111827_100%)] dark:text-slate-100">

    <div class="relative flex min-h-screen flex-col justify-center items-center p-6">
        <div class="absolute inset-x-0 top-0 -z-10 h-[24rem] bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.22),_transparent_45%),radial-gradient(circle_at_top_right,_rgba(125,211,252,0.18),_transparent_35%)] dark:bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.3),_transparent_40%),radial-gradient(circle_at_top_right,_rgba(59,130,246,0.18),_transparent_30%)]"></div>
        <div class="absolute left-[-4rem] top-32 -z-10 h-40 w-40 rounded-full bg-blue-300/30 blur-3xl dark:bg-blue-500/20"></div>
        <div class="absolute right-[-5rem] top-40 -z-10 h-56 w-56 rounded-full bg-sky-300/30 blur-3xl dark:bg-sky-500/15"></div>

        <div class="absolute top-4 right-4">
            <div class="inline-flex items-center rounded-full border border-slate-200/80 bg-white/85 p-1 text-sm shadow-sm backdrop-blur dark:border-slate-700/80 dark:bg-slate-900/80">
                <button type="button" data-theme-option="light" aria-pressed="false" class="rounded-full px-3 py-2 font-medium text-slate-600 transition hover:text-[#2563EB] data-[active=true]:bg-[#2563EB] data-[active=true]:text-white dark:text-slate-300 dark:hover:text-blue-300">
                    Terang
                </button>
                <button type="button" data-theme-option="dark" aria-pressed="false" class="rounded-full px-3 py-2 font-medium text-slate-600 transition hover:text-[#2563EB] data-[active=true]:bg-[#2563EB] data-[active=true]:text-white dark:text-slate-300 dark:hover:text-blue-300">
                    Gelap
                </button>
                <button type="button" data-theme-option="system" aria-pressed="false" class="rounded-full px-3 py-2 font-medium text-slate-600 transition hover:text-[#2563EB] data-[active=true]:bg-[#2563EB] data-[active=true]:text-white dark:text-slate-300 dark:hover:text-blue-300">
                    System
                </button>
            </div>
        </div>

        <div class="w-full max-w-md rounded-[2rem] border border-white/80 bg-white/85 p-8 shadow-2xl shadow-slate-900/10 backdrop-blur dark:border-white/10 dark:bg-slate-900/80 dark:shadow-black/30">
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto h-32 w-auto mb-3 login-logo">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    {{ config('app.name', 'Laravel Filament') }}
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Silakan masuk untuk mengakses panel layanan Anda.
                </p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="login" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Username atau Email</label>
                    <input type="text" name="login" id="login" value="{{ old('login') }}" required autofocus
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white/70 dark:bg-slate-950/40 focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-hidden text-sm dark:text-white transition duration-150 @error('login') border-red-500 dark:border-red-500 @enderror">
                    @error('login')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white/70 dark:bg-slate-950/40 focus:ring-2 focus:ring-[#2563EB] focus:border-[#2563EB] focus:outline-hidden text-sm dark:text-white transition duration-150">

                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-[#2563EB] dark:hover:text-blue-300 focus:outline-none cursor-pointer">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eye-slash-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center text-sm text-slate-600 dark:text-slate-400 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#2563EB] focus:ring-[#2563EB] dark:bg-slate-800 dark:border-slate-700 mr-2">
                        Ingat Saya
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-2.5 px-4 font-semibold text-sm bg-[#2563EB] hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg shadow-sm focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-[#2563EB] dark:focus:ring-offset-slate-900 cursor-pointer transition duration-150">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</body>
</html>

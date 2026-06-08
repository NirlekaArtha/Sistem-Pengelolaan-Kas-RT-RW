<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Sistem Pengelolaan Kas RT/RW') }}</title>

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
    <body class="min-h-full bg-[linear-gradient(180deg,#eff6ff_0%,#dbeafe_30%,#f8fafc_100%)] text-slate-900 transition-colors duration-300 selection:bg-[#2563EB] selection:text-white dark:bg-[linear-gradient(180deg,#020617_0%,#0f172a_40%,#111827_100%)] dark:text-slate-100">
        <div class="relative overflow-hidden">
            <div class="absolute inset-x-0 top-0 -z-10 h-[32rem] bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.24),_transparent_45%),radial-gradient(circle_at_top_right,_rgba(96,165,250,0.2),_transparent_35%),radial-gradient(circle_at_center,_rgba(15,23,42,0.1),_transparent_55%)] dark:bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.32),_transparent_40%),radial-gradient(circle_at_top_right,_rgba(59,130,246,0.2),_transparent_30%),radial-gradient(circle_at_center,_rgba(148,163,184,0.08),_transparent_55%)]"></div>
            <div class="absolute left-[-8rem] top-24 -z-10 h-56 w-56 rounded-full bg-blue-300/35 blur-3xl dark:bg-blue-500/20"></div>
            <div class="absolute right-[-6rem] top-40 -z-10 h-72 w-72 rounded-full bg-sky-300/40 blur-3xl dark:bg-sky-500/15"></div>

            <header class="mx-auto flex w-full max-w-7xl flex-col gap-5 px-6 py-6 lg:flex-row lg:items-center lg:justify-between lg:px-10">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/70 bg-white/80 shadow-lg shadow-blue-950/10 backdrop-blur dark:border-white/10 dark:bg-slate-900/70 dark:shadow-black/20">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo kas RT RW" class="h-10 w-10 object-contain">
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#2563EB] dark:text-blue-300">Digital Warga</p>
                        <h1 class="font-serif text-xl font-semibold tracking-tight text-slate-900 dark:text-white">
                            {{ config('app.name', 'Sistem Pengelolaan Kas RT/RW') }}
                        </h1>
                    </div>
                </div>

                <div class="flex flex-col gap-4 md:flex-row md:items-center">
                    <div class="inline-flex w-fit items-center rounded-full border border-slate-200/80 bg-white/80 p-1 text-sm shadow-sm backdrop-blur dark:border-slate-700/80 dark:bg-slate-900/75">
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

                    <nav class="hidden items-center gap-3 md:flex">
                        <a href="#fitur" class="text-sm font-medium text-slate-600 transition hover:text-[#2563EB] dark:text-slate-300 dark:hover:text-blue-300">Fitur</a>
                        <a href="#peran" class="text-sm font-medium text-slate-600 transition hover:text-[#2563EB] dark:text-slate-300 dark:hover:text-blue-300">Peran</a>
                        <a href="#laporan" class="text-sm font-medium text-slate-600 transition hover:text-[#2563EB] dark:text-slate-300 dark:hover:text-blue-300">Laporan</a>
                        <a href="{{ route('login') }}" class="rounded-full border border-slate-300 bg-white/80 px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:border-[#2563EB] hover:text-[#2563EB] dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-100 dark:hover:border-blue-400 dark:hover:text-blue-300">
                            Masuk Sistem
                        </a>
                    </nav>
                </div>
            </header>

            <main>
                <section class="mx-auto grid max-w-7xl gap-14 px-6 pb-20 pt-6 lg:grid-cols-[1.08fr_0.92fr] lg:items-center lg:px-10 lg:pb-24 lg:pt-10">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-blue-200/80 bg-white/70 px-4 py-2 text-sm font-medium text-[#2563EB] shadow-sm backdrop-blur dark:border-blue-400/20 dark:bg-slate-900/60 dark:text-blue-300">
                            <span class="h-2 w-2 rounded-full bg-[#2563EB]"></span>
                            Transparansi kas untuk pengurus dan warga
                        </div>

                        <h2 class="mt-6 max-w-3xl font-serif text-5xl font-semibold leading-tight tracking-tight text-slate-900 md:text-6xl dark:text-white">
                            Kelola kas RT/RW dengan alur yang lebih rapi, cepat, dan mudah dipantau bersama.
                        </h2>

                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-700 dark:text-slate-300">
                            Aplikasi ini membantu pencatatan iuran warga, kas bulanan RT dan RW, setoran antar tingkat, kasbon, slip gaji petugas, sampai laporan tahunan dalam satu sistem yang lebih tertib dan akuntabel.
                        </p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-[#2563EB] px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-900/20 transition hover:-translate-y-0.5 hover:bg-blue-700">
                                Login ke Dashboard
                            </a>
                            <a href="#fitur" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white/80 px-6 py-3.5 text-sm font-semibold text-slate-900 shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:border-[#2563EB] hover:text-[#2563EB] dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-100 dark:hover:border-blue-400 dark:hover:text-blue-300">
                                Lihat Fitur Utama
                            </a>
                        </div>

                        <dl class="mt-10 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl border border-white/80 bg-white/70 p-5 shadow-lg shadow-slate-900/5 backdrop-blur dark:border-white/10 dark:bg-slate-900/65 dark:shadow-black/20">
                                <dt class="text-sm text-slate-500 dark:text-slate-400">Pencatatan</dt>
                                <dd class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Iuran & Kas</dd>
                            </div>
                            <div class="rounded-3xl border border-white/80 bg-white/70 p-5 shadow-lg shadow-slate-900/5 backdrop-blur dark:border-white/10 dark:bg-slate-900/65 dark:shadow-black/20">
                                <dt class="text-sm text-slate-500 dark:text-slate-400">Dokumen</dt>
                                <dd class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Kwitansi & Slip</dd>
                            </div>
                            <div class="rounded-3xl border border-white/80 bg-white/70 p-5 shadow-lg shadow-slate-900/5 backdrop-blur dark:border-white/10 dark:bg-slate-900/65 dark:shadow-black/20">
                                <dt class="text-sm text-slate-500 dark:text-slate-400">Pelaporan</dt>
                                <dd class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Bulanan & Tahunan</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-6 top-10 hidden h-24 w-24 rounded-[2rem] border border-blue-200/70 bg-white/60 blur-[1px] md:block dark:border-blue-400/15 dark:bg-slate-900/45"></div>
                        <div class="absolute -right-3 top-20 hidden h-32 w-32 rounded-full bg-blue-200/70 blur-2xl md:block dark:bg-blue-500/20"></div>

                        <div class="relative overflow-hidden rounded-[2rem] border border-white/70 bg-slate-900 p-6 text-white shadow-2xl shadow-slate-900/20 dark:border-white/10 dark:bg-slate-950">
                            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(37,99,235,0.32),_transparent_35%),linear-gradient(135deg,rgba(255,255,255,0.06),transparent_55%)]"></div>
                            <div class="relative">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm text-blue-200">Ringkasan Operasional</p>
                                        <h3 class="mt-1 text-2xl font-semibold">Kas lingkungan yang lebih terbaca</h3>
                                    </div>
                                    <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-blue-200">
                                        RT / RW / Warga
                                    </span>
                                </div>

                                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                    <div class="rounded-3xl border border-white/10 bg-white/8 p-5">
                                        <p class="text-sm text-slate-300">Data yang dikelola</p>
                                        <ul class="mt-4 space-y-3 text-sm text-slate-100">
                                            <li class="rounded-2xl bg-white/8 px-4 py-3">Iuran warga per jenis dan periode</li>
                                            <li class="rounded-2xl bg-white/8 px-4 py-3">Kas RT/RW dan pengeluaran rutin</li>
                                            <li class="rounded-2xl bg-white/8 px-4 py-3">Setoran, kasbon, dan gaji petugas</li>
                                        </ul>
                                    </div>

                                    <div class="rounded-3xl border border-white/10 bg-slate-50 p-5 text-slate-900 dark:bg-slate-900 dark:text-slate-100">
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Manfaat langsung</p>
                                        <div class="mt-4 space-y-4">
                                            <div class="rounded-2xl bg-white px-4 py-3 shadow-sm dark:bg-slate-800">
                                                <p class="text-xs uppercase tracking-[0.24em] text-[#2563EB]">Transparan</p>
                                                <p class="mt-1 text-sm font-medium">Laporan lebih mudah dipertanggungjawabkan.</p>
                                            </div>
                                            <div class="rounded-2xl bg-white px-4 py-3 shadow-sm dark:bg-slate-800">
                                                <p class="text-xs uppercase tracking-[0.24em] text-[#2563EB]">Tertib</p>
                                                <p class="mt-1 text-sm font-medium">Arus kas tercatat konsisten antar level pengurus.</p>
                                            </div>
                                            <div class="rounded-2xl bg-white px-4 py-3 shadow-sm dark:bg-slate-800">
                                                <p class="text-xs uppercase tracking-[0.24em] text-[#2563EB]">Efisien</p>
                                                <p class="mt-1 text-sm font-medium">Proses administrasi tidak lagi terpecah di banyak catatan.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 rounded-3xl border border-white/10 bg-white/8 p-5">
                                    <p class="text-sm text-slate-300">Alur singkat</p>
                                    <div class="mt-4 grid gap-3 md:grid-cols-3">
                                        <div class="rounded-2xl border border-white/10 bg-white/8 px-4 py-4">
                                            <p class="text-xs uppercase tracking-[0.24em] text-blue-200">1</p>
                                            <p class="mt-2 text-sm font-medium">Input transaksi, iuran, dan pengeluaran</p>
                                        </div>
                                        <div class="rounded-2xl border border-white/10 bg-white/8 px-4 py-4">
                                            <p class="text-xs uppercase tracking-[0.24em] text-blue-200">2</p>
                                            <p class="mt-2 text-sm font-medium">Validasi data sesuai peran pengguna</p>
                                        </div>
                                        <div class="rounded-2xl border border-white/10 bg-white/8 px-4 py-4">
                                            <p class="text-xs uppercase tracking-[0.24em] text-blue-200">3</p>
                                            <p class="mt-2 text-sm font-medium">Cetak dan bagikan laporan periodik</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="fitur" class="mx-auto max-w-7xl px-6 py-6 lg:px-10">
                    <div class="max-w-2xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[#2563EB] dark:text-blue-300">Fitur Utama</p>
                        <h3 class="mt-3 font-serif text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl dark:text-white">
                            Dirancang untuk kebutuhan administrasi lingkungan yang nyata.
                        </h3>
                    </div>

                    <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                        <article class="rounded-[1.75rem] border border-white/80 bg-white/80 p-6 shadow-lg shadow-slate-900/5 backdrop-blur dark:border-white/10 dark:bg-slate-900/70 dark:shadow-black/20">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-lg font-semibold text-[#2563EB] dark:bg-blue-500/15 dark:text-blue-300">01</div>
                            <h4 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">Pengelolaan Iuran Warga</h4>
                            <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">
                                Catat pembayaran iuran berdasarkan jenis, warga, dan periode dengan riwayat yang lebih mudah ditelusuri.
                            </p>
                        </article>

                        <article class="rounded-[1.75rem] border border-white/80 bg-white/80 p-6 shadow-lg shadow-slate-900/5 backdrop-blur dark:border-white/10 dark:bg-slate-900/70 dark:shadow-black/20">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-lg font-semibold text-[#2563EB] dark:bg-blue-500/15 dark:text-blue-300">02</div>
                            <h4 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">Kas RT dan RW</h4>
                            <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">
                                Pantau pemasukan, pengeluaran, dan saldo kas untuk kebutuhan operasional harian maupun program lingkungan.
                            </p>
                        </article>

                        <article class="rounded-[1.75rem] border border-white/80 bg-white/80 p-6 shadow-lg shadow-slate-900/5 backdrop-blur dark:border-white/10 dark:bg-slate-900/70 dark:shadow-black/20">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-lg font-semibold text-[#2563EB] dark:bg-blue-500/15 dark:text-blue-300">03</div>
                            <h4 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">Bukti Administrasi</h4>
                            <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">
                                Siapkan kwitansi setoran, bukti iuran, dan slip gaji agar arsip administrasi lebih lengkap dan seragam.
                            </p>
                        </article>

                        <article class="rounded-[1.75rem] border border-white/80 bg-white/80 p-6 shadow-lg shadow-slate-900/5 backdrop-blur dark:border-white/10 dark:bg-slate-900/70 dark:shadow-black/20">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-lg font-semibold text-[#2563EB] dark:bg-blue-500/15 dark:text-blue-300">04</div>
                            <h4 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">Laporan Periodik</h4>
                            <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">
                                Buat laporan kas bulanan dan tahunan untuk evaluasi, rapat warga, atau pertanggungjawaban pengurus.
                            </p>
                        </article>
                    </div>
                </section>

                <section id="peran" class="mx-auto max-w-7xl px-6 py-16 lg:px-10">
                    <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                        <div class="rounded-[2rem] border border-slate-200/70 bg-slate-900 p-8 text-white shadow-2xl shadow-slate-900/15 dark:border-white/10 dark:bg-slate-950">
                            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-blue-200">Akses Peran</p>
                            <h3 class="mt-4 font-serif text-3xl font-semibold tracking-tight">
                                Setiap pengguna masuk ke panel yang sesuai dengan tugasnya.
                            </h3>
                            <p class="mt-4 text-sm leading-7 text-slate-300">
                                Sistem login mengarahkan pengguna ke area kerja masing-masing agar pengelolaan data lebih fokus dan aman.
                            </p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-3">
                            <article class="rounded-[1.75rem] border border-white/80 bg-white/85 p-6 shadow-lg shadow-slate-900/5 dark:border-white/10 dark:bg-slate-900/75 dark:shadow-black/20">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#2563EB] dark:text-blue-300">RW</p>
                                <h4 class="mt-4 text-xl font-semibold text-slate-900 dark:text-white">Koordinasi tingkat wilayah</h4>
                                <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">
                                    Mengelola kas RW, data RT, slip gaji, dan laporan gabungan untuk kebutuhan pengurus wilayah.
                                </p>
                            </article>

                            <article class="rounded-[1.75rem] border border-white/80 bg-white/85 p-6 shadow-lg shadow-slate-900/5 dark:border-white/10 dark:bg-slate-900/75 dark:shadow-black/20">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#2563EB] dark:text-blue-300">RT</p>
                                <h4 class="mt-4 text-xl font-semibold text-slate-900 dark:text-white">Operasional tingkat lingkungan</h4>
                                <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">
                                    Mencatat warga, iuran, kas RT, dan setoran ke RW dengan alur administrasi yang lebih tertib.
                                </p>
                            </article>

                            <article class="rounded-[1.75rem] border border-white/80 bg-white/85 p-6 shadow-lg shadow-slate-900/5 dark:border-white/10 dark:bg-slate-900/75 dark:shadow-black/20">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#2563EB] dark:text-blue-300">Warga</p>
                                <h4 class="mt-4 text-xl font-semibold text-slate-900 dark:text-white">Akses informasi pribadi</h4>
                                <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">
                                    Melihat informasi iuran dan status pembayaran melalui panel yang lebih sederhana dan relevan.
                                </p>
                            </article>
                        </div>
                    </div>
                </section>

                <section id="laporan" class="mx-auto max-w-7xl px-6 pb-24 lg:px-10">
                    <div class="overflow-hidden rounded-[2rem] border border-blue-900/10 bg-[linear-gradient(135deg,#0f172a_0%,#111827_45%,#1d4ed8_100%)] p-8 text-white shadow-2xl shadow-slate-900/20 md:p-10 dark:border-white/10 dark:bg-[linear-gradient(135deg,#020617_0%,#0f172a_50%,#1e3a8a_100%)]">
                        <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-blue-200">Siap Digunakan</p>
                                <h3 class="mt-4 max-w-3xl font-serif text-3xl font-semibold tracking-tight md:text-4xl">
                                    Bangun kepercayaan warga lewat pencatatan yang konsisten dan laporan yang mudah dibaca.
                                </h3>
                                <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 md:text-base">
                                    Landing page ini menjadi pintu masuk yang menjelaskan manfaat sistem, sementara dashboard menangani operasional harian pengurus dan warga.
                                </p>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-[#2563EB] px-6 py-3.5 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-blue-700">
                                    Masuk Sekarang
                                </a>
                                <a href="#peran" class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 px-6 py-3.5 text-sm font-semibold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/15">
                                    Lihat Peran Pengguna
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>

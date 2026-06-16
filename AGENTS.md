# AGENTS.md — Sistem Pengelolaan Kas RT/RW

## Stack

- **Laravel 13** + **Filament 5** (multi-panel admin)
- **MariaDB** (dev), **SQLite `:memory:`** (tests) — see `phpunit.xml`
- **Tailwind v4** via `@tailwindcss/vite` plugin
- **barryvdh/laravel-dompdf** for PDF export of monthly reports, salary slips, receipts
- **Laravel Octane** (FrankenPHP hinted in `.gitignore`)
- Indonesian locale (`APP_LOCALE=id`, `APP_FAKER_LOCALE=id_ID`)

## Multi-Panel Architecture

Three Filament panels, split by `User` `role` enum (`Warga`/`RT`/`RW`):

| Panel | Path   | Provider                        | Notes                          |
|-------|--------|---------------------------------|----------------------------------|
| RW    | `/rw`  | `RwPanelProvider`               | `->default()`, primary panel     |
| RT    | `/rt`  | `RtPanelProvider`               |                                 |
| Warga | `/warga` | `WargaPanelProvider`          |                                 |

**Auth**: Custom `LoginController` at `/login`, not Filament's built-in. `RedirectToProperPanel` middleware in each panel redirects users to correct panel based on role.

App resources live under `app/Filament/{Rw,Rt,Warga}/Resources/`, pages under `app/Filament/Pages/`.

## Key Commands

```bash
# Full setup (composer install → .env → key:generate → migrate → npm install → build)
composer setup

# Dev server (concurrent: artisan serve + queue:work + pail logs + Vite)
composer dev

# Run tests (clears config first)
composer test

# Run focused tests
php artisan test tests/Feature/XTest.php
php artisan test --filter=method_name

# Migrate + seed (creates admin: admin@gmail.com / admin123)
php artisan migrate --seed

# Code style (Laravel Pint — no custom config file, uses defaults)
./vendor/bin/pint
```

## Important Conventions

- **Observers recalculate financial totals** — `app/Observers/` contains 9 observers that trigger `KasBulanan{Rt,Rw}Service::recalculate()` after related model saves. Use `saveQuietly()` in service methods to prevent observer loops.
- **Chain recalculation** — `recalculateChain($id, $fromPeriode)` re-runs all months from a given period forward because `saldo_awal` inherits from the previous month's `saldo_akhir`.
- **Database drivers**: `DB_CONNECTION=mariadb` in `.env`. Session, cache, queue all use `database` driver. Tests override to SQLite.
- **Models** (16) mirror the 18 DB tables. Key models: `User` (role enum), `Warga`, `RT`, `RW`, `Petugas`, `IuranWarga`, `KasRT`, `KasRW`, `SetoranRW`, `SlipGaji`.
- **PDF export paths** stored in `file_path` columns on `KasBulanan{RT,RW}`, `SlipGaji`, `KwitansiIuranWarga`, `KwitansiSetoranRW`.
- **Cash flow**: RT manages own kas + iuran warga + setoran to RW. RW consolidates from RTs + manages own kas + petugas payroll.
- **Navigation groups** across all panels: `Data Master`, `Transaksi`, `Laporan & Rekap`.
- `Context.md` has the full DB schema + business flow documentation (kept in repo, listed in `.gitignore`).

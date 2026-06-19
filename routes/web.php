<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KasBulananRTController;
use App\Http\Controllers\KasBulananRWController;
use App\Http\Controllers\SlipGajiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // RW routes
    Route::get('/rw/kas-bulanan/{record}/preview', [
        KasBulananRWController::class,
        'preview',
    ])->name('rw.kas-bulanan.preview');
    Route::get('/rw/kas-bulanan/{record}/download', [
        KasBulananRWController::class,
        'download',
    ])->name('rw.kas-bulanan.download');
    Route::get('/rw/kas-tahunan/{tahun}/preview', [
        KasBulananRWController::class,
        'previewTahunan',
    ])->name('rw.kas-tahunan.preview');
    Route::get('/rw/kas-tahunan/{tahun}/download', [
        KasBulananRWController::class,
        'downloadTahunan',
    ])->name('rw.kas-tahunan.download');

    // RT routes
    Route::get('/rt/kas-bulanan/{record}/preview', [
        KasBulananRTController::class,
        'preview',
    ])->name('rt.kas-bulanan.preview');
    Route::get('/rt/kas-bulanan/{record}/download', [
        KasBulananRTController::class,
        'download',
    ])->name('rt.kas-bulanan.download');
    Route::get('/rt/kas-tahunan/{tahun}/preview', [
        KasBulananRTController::class,
        'previewTahunan',
    ])->name('rt.kas-tahunan.preview');
    Route::get('/rt/kas-tahunan/{tahun}/download', [
        KasBulananRTController::class,
        'downloadTahunan',
    ])->name('rt.kas-tahunan.download');

    Route::get('/rw/slip-gaji/preview-all', [
        SlipGajiController::class,
        'previewAll',
    ])->name('rw.slip-gaji.preview-all');
    Route::get('/rw/slip-gaji/download-all', [
        SlipGajiController::class,
        'downloadAll',
    ])->name('rw.slip-gaji.download-all');
    Route::get('/rw/slip-gaji/{record}/preview', [
        SlipGajiController::class,
        'preview',
    ])->name('rw.slip-gaji.preview');
    Route::get('/rw/slip-gaji/{record}/download', [
        SlipGajiController::class,
        'download',
    ])->name('rw.slip-gaji.download');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name(
        'login',
    );
    Route::post('login', [LoginController::class, 'login']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');
// Biar kalau ada route lain langsung reroute, pake kalo keliatan ngeselin/masa prod
// Route::fallback(function () {
//     return redirect("/auth");
// });

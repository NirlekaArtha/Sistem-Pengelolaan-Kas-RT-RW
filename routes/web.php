<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
});

Route::redirect('/login', '/auth/login')->name('login');

use App\Http\Controllers\KasBulananRWController;
use App\Http\Controllers\SlipGajiController;

Route::middleware(['auth'])->group(function () {
    Route::get('/rw/kas-bulanan/{record}/preview', [KasBulananRWController::class, 'preview'])->name('rw.kas-bulanan.preview');
    Route::get('/rw/kas-bulanan/{record}/download', [KasBulananRWController::class, 'download'])->name('rw.kas-bulanan.download');
    Route::get('/rw/kas-tahunan/{tahun}/preview', [KasBulananRWController::class, 'previewTahunan'])->name('rw.kas-tahunan.preview');
    Route::get('/rw/kas-tahunan/{tahun}/download', [KasBulananRWController::class, 'downloadTahunan'])->name('rw.kas-tahunan.download');

    Route::get('/rw/slip-gaji/preview-all', [SlipGajiController::class, 'previewAll'])->name('rw.slip-gaji.preview-all');
    Route::get('/rw/slip-gaji/download-all', [SlipGajiController::class, 'downloadAll'])->name('rw.slip-gaji.download-all');
    Route::get('/rw/slip-gaji/{record}/preview', [SlipGajiController::class, 'preview'])->name('rw.slip-gaji.preview');
    Route::get('/rw/slip-gaji/{record}/download', [SlipGajiController::class, 'download'])->name('rw.slip-gaji.download');
});

// Biar kalau ada route lain langsung reroute, pake kalo keliatan ngeselin/masa prod
// Route::fallback(function () {
//     return redirect("/auth");
// });

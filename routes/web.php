<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
});

use App\Http\Controllers\KasBulananRWController;

Route::middleware(['auth'])->group(function () {
    Route::get('/rw/kas-bulanan/{record}/preview', [KasBulananRWController::class, 'preview'])->name('rw.kas-bulanan.preview');
    Route::get('/rw/kas-bulanan/{record}/download', [KasBulananRWController::class, 'download'])->name('rw.kas-bulanan.download');
});

// Biar kalau ada route lain langsung reroute, pake kalo keliatan ngeselin/masa prod
// Route::fallback(function () {
//     return redirect("/auth");
// });

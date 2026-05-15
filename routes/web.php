<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
});

// Biar kalau ada route lain langsung reroute, pake kalo keliatan ngeselin/masa prod
// Route::fallback(function () {
//     return redirect("/auth");
// });

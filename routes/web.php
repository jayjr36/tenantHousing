<?php

use Illuminate\Support\Facades\Route;
use App\Models\MeterReading;
use App\Http\Controllers\MeterController;
use App\Http\Controllers\WalletController;

Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/home', function () {
//     return view('meter.show');
// });

Route::get('/meter', [MeterController::class, 'show'])->name('meter.show');

Route::get('/meters/{meterId}/realtime-readings', [MeterController::class, 'realtimeReadings'])->name('realtimeReadings');
Route::post('/meters/add-units', [MeterController::class, 'addUnits'])->name('addUnits');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// routes/web.php

Route::post('/wallet/add-money', [WalletController::class, 'addMoney'])->name('wallet.addMoney');

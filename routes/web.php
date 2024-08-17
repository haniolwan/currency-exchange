<?php

use App\Http\Controllers\AmountController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::resource('exchange_rates', ExchangeRateController::class);
Route::resource('amounts', AmountController::class);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

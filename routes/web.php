<?php

use App\Http\Controllers\AmountController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [FileController::class, 'createFiles']);

Route::resource('exchange_rates', ExchangeRateController::class);
Route::get('exchange_rates/update/{id}', [ExchangeRateController::class, 'updateRate'])
    ->name('update.rate');

Route::resource('currencies', CurrencyController::class);
Route::get('currencies/update/{id}', [CurrencyController::class, 'updateCurrencyPage'])
    ->name('update.currency');

Route::resource('amounts', AmountController::class);
Route::get('amounts/update/{id}', [AmountController::class, 'updateAmountPage'])
    ->name('update.amount');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

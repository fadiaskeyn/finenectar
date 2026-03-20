<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/syarat-ketentuan', 'terms')->name('terms');
Route::view('/kontak-cs', 'contact')->name('contact');

Route::redirect('/admin', '/admin/orders');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->middleware('throttle:8,1')
    ->name('checkout.store');
Route::post('/payments/tripay/callback', [CheckoutController::class, 'callback'])->name('payments.tripay.callback');

Route::middleware('auth')->group(function () {
    Route::get('/admin/orders', [CheckoutController::class, 'index'])->name('admin.orders.index');
});

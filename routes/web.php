<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WhatsAppSessionController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'landing'])->name('home');
Route::get('/dev', [ProductController::class, 'dev'])->name('dev');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show');

Route::view('/syarat-ketentuan', 'terms')->name('terms');
Route::view('/kontak-cs', 'contact')->name('contact');
Route::view('/kebijakan-privasi', 'privacy')->name('privacy');

Route::redirect('/admin', '/admin/orders');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->middleware('throttle:8,1')
    ->name('checkout.store');
Route::post('/payments/tripay/callback', [CheckoutController::class, 'callback'])->name('payments.tripay.callback');

Route::middleware('auth')->group(function () {
    Route::get('/admin/orders', [CheckoutController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/whatsapp/login', [WhatsAppSessionController::class, 'index'])->name('admin.whatsapp.index');
    Route::post('/admin/whatsapp/login/start', [WhatsAppSessionController::class, 'start'])->name('admin.whatsapp.start');
    Route::post('/admin/whatsapp/login/stop', [WhatsAppSessionController::class, 'stop'])->name('admin.whatsapp.stop');
    Route::get('/admin/whatsapp/login/status', [WhatsAppSessionController::class, 'status'])->name('admin.whatsapp.status');
    Route::get('/admin/products', [ProductController::class, 'adminIndex'])->name('admin.products.index');
    Route::get('/admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
});

<?php

use App\Http\Controllers\OrderCheckoutController;
use App\Http\Controllers\OrderPreviewController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::get('/order/{order}', OrderPreviewController::class)->name('order.preview');
Route::get('/order/{collection}/checkout', [OrderCheckoutController::class, 'form'])->name('order.checkout');
Route::post('/order/{collection}/checkout', [OrderCheckoutController::class, 'store']);

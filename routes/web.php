<?php

use App\Http\Controllers\OrderPreviewController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::get('/order/{order}/preview', OrderPreviewController::class)->name('order.preview');

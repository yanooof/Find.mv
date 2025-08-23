<?php

use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index'])->name('main-page');
Route::get('/search', [ProductController::class, 'search'])->name('search');
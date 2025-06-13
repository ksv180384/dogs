<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('dogs', [\App\Http\Controllers\Admin\DogController::class, 'index'])->name('admin.dogs.index');
    Route::get('dogs/create', [\App\Http\Controllers\Admin\DogController::class, 'create'])->name('admin.dog.create');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

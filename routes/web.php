<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return redirect('/home');
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard dan Customer Management
Route::middleware('auth')->group(function () {
    Route::get('/home', HomeController::class)->name('home');
    Route::resource('customers', CustomerController::class);

    // Profile routes (sudah ada dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Route::group(['prefix' => 'cpanel'], function () {
//     Auth::routes([
//         'register' => false,
//         'verify' => false,
//     ]);

//     Route::group(['middleware' => ['auth']], function () {
//         Route::get('/home', HomeController::class)->name('home');
//         Route::resource('customers', CustomerController::class);
//         // routes lainnya...
//     });
// });

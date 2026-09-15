<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PetController as AdminPetController;
use App\Http\Controllers\Admin\ShelterProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Guest Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pets/{pet}', [PetController::class, 'show'])->name('pets.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Owner (Authenticated Admin) Management Dashboard Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pets', AdminPetController::class);
    Route::get('/shelter-profile', [ShelterProfileController::class, 'edit'])->name('shelter-profile.edit');
    Route::put('/shelter-profile', [ShelterProfileController::class, 'update'])->name('shelter-profile.update');
});

// Alias /dashboard directly to /admin/dashboard for convenience
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth');

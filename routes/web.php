<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Pelapor\DashboardController as PelaporDashboard;
use App\Http\Controllers\Pelapor\ComplaintController as PelaporComplaint;
use App\Http\Controllers\It\DashboardController as ItDashboard;
use App\Http\Controllers\It\ComplaintController as ItComplaint;
use App\Http\Controllers\It\HandlingController as ItHandling;
use App\Http\Controllers\It\PcController as ItPc;
use App\Http\Controllers\It\CategoryController as ItCategory;
use App\Http\Controllers\It\UserController as ItUser;

Route::get('/', fn () => redirect()->route('login'));

// ==================== AUTH ====================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==================== NOTIFIKASI ====================
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
});

// ==================== CHAT ====================
Route::middleware(['auth'])->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/{complaint}', [ChatController::class, 'show'])->name('show');
    Route::get('/{chat}/fetch', [ChatController::class, 'fetch'])->name('fetch');
    Route::post('/{chat}/send', [ChatController::class, 'send'])->name('send');
});

// ==================== PELAPOR ====================
Route::middleware(['auth', 'role:pelapor'])
    ->prefix('pelapor')
    ->name('pelapor.')
    ->group(function () {
        Route::get('/dashboard', [PelaporDashboard::class, 'index'])->name('dashboard');

        Route::get('/complaints', [PelaporComplaint::class, 'index'])->name('complaints.index');
        Route::get('/complaints/create', [PelaporComplaint::class, 'create'])->name('complaints.create');
        Route::post('/complaints', [PelaporComplaint::class, 'store'])->name('complaints.store');
        Route::post('/complaints/check-recurring', [PelaporComplaint::class, 'checkRecurring'])
            ->name('complaints.check-recurring');
        Route::get('/complaints/{complaint}', [PelaporComplaint::class, 'show'])->name('complaints.show');
        Route::post('/complaints/{complaint}/confirm-recurring', [PelaporComplaint::class, 'confirmRecurring'])
            ->name('complaints.confirm-recurring');
    });

// ==================== IT ====================
Route::middleware(['auth', 'role:it'])
    ->prefix('it')
    ->name('it.')
    ->group(function () {
        Route::get('/dashboard', [ItDashboard::class, 'index'])->name('dashboard');

        Route::get('/complaints', [ItComplaint::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [ItComplaint::class, 'show'])->name('complaints.show');
        Route::patch('/complaints/{complaint}/status', [ItComplaint::class, 'updateStatus'])->name('complaints.update-status');
        Route::patch('/complaints/{complaint}/mark-recurring', [ItComplaint::class, 'markRecurring'])->name('complaints.mark-recurring');

        Route::get('/complaints/{complaint}/handle', [ItHandling::class, 'create'])->name('handling.create');
        Route::post('/complaints/{complaint}/handle', [ItHandling::class, 'store'])->name('handling.store');

        Route::get('/pcs/{pc}/history', [ItPc::class, 'history'])->name('pcs.history');
        Route::resource('pcs', ItPc::class);
        Route::resource('categories', ItCategory::class)->except(['show']);
        Route::resource('users', ItUser::class)->except(['show']);
    });
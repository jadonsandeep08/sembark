<?php

use App\Http\Controllers\Admin\InvitationController as AdminInvitationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InvitationAcceptController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\InvitationController as SuperAdminInvitationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/invitations/accept/{token}', [InvitationAcceptController::class, 'show'])
    ->name('invitations.show');
Route::post('/invitations/accept/{token}', [InvitationAcceptController::class, 'accept'])
    ->name('invitations.accept');

Route::get('/s/{shortCode}', [ShortUrlController::class, 'redirect'])
    ->name('short-urls.redirect');

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/short-urls', [ShortUrlController::class, 'index'])
        ->name('short-urls.index');

    Route::middleware('role:admin,member')->group(function () {
        Route::get('/short-urls/create', [ShortUrlController::class, 'create'])
            ->name('short-urls.create');
        Route::post('/short-urls', [ShortUrlController::class, 'store'])
            ->name('short-urls.store');
    });

    Route::middleware('role:super_admin')->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/invitations/create', [SuperAdminInvitationController::class, 'create'])->name('invitations.create');
        Route::post('/invitations', [SuperAdminInvitationController::class, 'store'])->name('invitations.store');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/invitations/create', [AdminInvitationController::class, 'create'])->name('invitations.create');
        Route::post('/invitations', [AdminInvitationController::class, 'store'])->name('invitations.store');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

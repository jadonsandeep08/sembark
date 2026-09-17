<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\InvitationController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit');

// Authenticated routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })
        ->middleware('role:super_admin')
        ->name('dashboard');

    // SuperAdmin only
    Route::middleware('role:super_admin')->group(function () {

        // Company
        Route::get(
            '/superadmin/companies/create',
            [CompanyController::class, 'create']
        )->name('superadmin.companies.create');

        Route::post(
            '/superadmin/companies',
            [CompanyController::class, 'store']
        )->name('superadmin.companies.store');

        // Admin Invitation
        Route::get(
            '/superadmin/invitations/create',
            [InvitationController::class, 'create']
        )->name('superadmin.invitations.create');

        Route::post(
            '/superadmin/invitations',
            [InvitationController::class, 'store']
        )->name('superadmin.invitations.store');
    });

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\OTPLoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MembershipSupervisorController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\ChairmanController;

// Home route
Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return redirect()->route('admin.dashboard'); // Change this to match your default role-based route
})->name('home');

// Authentication routes
Auth::routes();

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// OTP-based login routes
Route::post('/login/request-otp', [OTPLoginController::class, 'requestOTP'])->name('login.request-otp');
Route::post('/login/verify-otp', [OTPLoginController::class, 'verifyOTP'])->name('login.verify-otp');

// Registration form routes
Route::get('/register/{form}', [RegistrationController::class, 'showForm'])->name('register.show');
Route::post('/register/{form}', [RegistrationController::class, 'submitForm'])->name('register.submit');
Route::get('/registrations/{id}/download-pdf', [RegistrationController::class, 'downloadPDF'])->name('registrations.download-pdf');
Route::get('/registration/download/{id}', [RegistrationController::class, 'downloadPDF'])->name('registration.download');

// Protected routes requiring authentication
Route::middleware(['auth'])->group(function () {

    // Workflow routes
    Route::post('/registrations/{id}/verify-documents', [RegistrationController::class, 'verifyDocuments'])->name('registrations.verify');
    Route::post('/registrations/{id}/collect-fee', [RegistrationController::class, 'collectFee'])->name('registrations.fee');
    Route::post('/registrations/{id}/audit-documents', [RegistrationController::class, 'auditDocuments'])->name('registrations.audit');
    Route::post('/registrations/{id}/approve-provisional', [RegistrationController::class, 'approveProvisionalMembership'])->name('registrations.provisional');
    Route::post('/registrations/{id}/grant-final-approval', [RegistrationController::class, 'grantFinalApproval'])->name('registrations.final');

    // Admin routes
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/registrations/{id}', [AdminController::class, 'show'])->name('admin.show');
    Route::post('/admin/registrations/{id}/approve', [AdminController::class, 'approve'])->name('admin.approve');
    Route::post('/admin/registrations/{id}/reject', [AdminController::class, 'reject'])->name('admin.reject');

    // Role-based dashboards
        Route::get('/supervisor/dashboard', [MembershipSupervisorController::class, 'index'])->name('supervisor.dashboard');
        Route::get('/cashier/dashboard', [CashierController::class, 'index'])->name('cashier.dashboard');
        Route::get('/accounts/dashboard', [AccountsController::class, 'index'])->name('accounts.dashboard');
        Route::get('/secretary/dashboard', [SecretaryController::class, 'index'])->name('secretary.dashboard');
        Route::get('/chairman/dashboard', [ChairmanController::class, 'index'])->name('chairman.dashboard');

});

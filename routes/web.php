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
use App\Http\Controllers\MemberController;

// Home route (Redirects based on role)
Route::get('/', function () {
    return view('welcome');
});

// Redirect based on user role
Route::get('/home', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'membership_supervisor' => redirect()->route('supervisor.dashboard'),
            'cashier' => redirect()->route('cashier.dashboard'),
            'accounts_audit' => redirect()->route('accounts.dashboard'),
            'dg_secretary' => redirect()->route('secretary.dashboard'),
            'chairman_president' => redirect()->route('chairman.dashboard'),
            'member' => redirect()->route('member.dashboard'),
            default => redirect('/'),
        };
    }
    return redirect('/');
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

// Protected routes requiring authentication
Route::middleware(['auth'])->group(function () {

    // **Workflow Routes**
    Route::post('/registrations/{id}/verify-documents', [RegistrationController::class, 'verifyDocuments'])->name('registrations.verify');
    Route::post('/registrations/{id}/collect-fee', [RegistrationController::class, 'collectFee'])->name('registrations.fee');
    Route::post('/registrations/{id}/audit-documents', [RegistrationController::class, 'auditDocuments'])->name('registrations.audit');
    Route::post('/registrations/{id}/approve-provisional', [RegistrationController::class, 'approveProvisionalMembership'])->name('registrations.provisional');
    Route::post('/registrations/{id}/grant-final-approval', [RegistrationController::class, 'grantFinalApproval'])->name('registrations.final');
    Route::post('/registrations/{id}/forward-to-chairman', [RegistrationController::class, 'forwardToChairman'])->name('registrations.forward_to_chairman');

    // **Admin Routes**
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/registrations/{id}', [AdminController::class, 'show'])->name('show');
        Route::post('/registrations/{id}/approve', [AdminController::class, 'approve'])->name('approve');
        Route::post('/registrations/{id}/reject', [AdminController::class, 'reject'])->name('reject');
    });

    // **Membership Supervisor Routes**
    Route::prefix('supervisor')->name('supervisor.')->group(function () {
        Route::get('/dashboard', [MembershipSupervisorController::class, 'index'])->name('dashboard');
        Route::get('/registrations/{id}', [MembershipSupervisorController::class, 'show'])->name('show');
        Route::post('/registrations/{id}/reject', [MembershipSupervisorController::class, 'rejectApplication'])->name('reject');
    });

    // **Cashier Routes**
    Route::prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/dashboard', [CashierController::class, 'index'])->name('dashboard');
        Route::get('/registrations/{id}', [CashierController::class, 'show'])->name('show');
        Route::post('/collect-fee/{id}', [CashierController::class, 'collectFee'])->name('collect-fee');
        Route::get('/print-receipt/{id}', [CashierController::class, 'printReceipt'])->name('print-receipt');
        Route::get('/receipt/{id}', [CashierController::class, 'showReceipt'])->name('receipt');
    });

    // **Accounts Audit Routes**
    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/dashboard', [AccountsController::class, 'index'])->name('dashboard');
        Route::get('/registrations/{id}', [AccountsController::class, 'show'])->name('show');
        Route::post('/registrations/{id}/audit', [AccountsController::class, 'auditDocuments'])->name('audit');
    });

    // **Secretary Routes**
    Route::prefix('secretary')->name('secretary.')->group(function () {
        Route::get('/dashboard', [SecretaryController::class, 'index'])->name('dashboard');
        Route::get('/registrations/{id}', [SecretaryController::class, 'show'])->name('show');
        Route::post('/assign-membership/{id}', [SecretaryController::class, 'assignMembershipNumber'])->name('assign-membership');
        Route::post('/approve-provisional/{id}', [SecretaryController::class, 'approveProvisionalMembership'])->name('approve-provisional');
    });

    // **Chairman Routes**
    Route::prefix('chairman')->name('chairman.')->group(function () {
        Route::get('/dashboard', [ChairmanController::class, 'index'])->name('dashboard');
        Route::get('/registrations/{id}', [ChairmanController::class, 'show'])->name('show');
        Route::post('/approve/{id}', [ChairmanController::class, 'approveMembership'])->name('approve');
        Route::post('/reject/{id}', [ChairmanController::class, 'rejectMembership'])->name('reject');
    });

    // **Member Routes**
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', [MemberController::class, 'index'])->name('dashboard');
        Route::post('/renew-membership', [MemberController::class, 'renewMembership'])->name('renew');
        Route::post('/request-visa-letter', [MemberController::class, 'requestVisaLetter'])->name('visa');
    });
});

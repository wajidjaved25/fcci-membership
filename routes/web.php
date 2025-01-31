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
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Response;

// Home route (Redirects based on role)
Route::get('/', function () {
    return view('welcome');
});

// Redirect based on user role
Route::get('/home', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        switch ($role) {
            case 'admin': return redirect()->route('admin.dashboard');
            case 'membership_supervisor': return redirect()->route('supervisor.dashboard');
            case 'cashier': return redirect()->route('cashier.dashboard');
            case 'accounts_audit': return redirect()->route('accounts.dashboard');
            case 'dg_secretary': return redirect()->route('secretary.dashboard');
            case 'chairman_president': return redirect()->route('chairman.dashboard');
            default: return redirect('/');
        }
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

    // Show routes for detailed registration view for each role
    Route::get('/supervisor/registrations/{id}', [MembershipSupervisorController::class, 'show'])->name('supervisor.show');
    Route::get('/cashier/registrations/{id}', [CashierController::class, 'show'])->name('cashier.show');
    Route::get('/accounts/registrations/{id}', [AccountsController::class, 'show'])->name('accounts.show');
    Route::get('/secretary/registrations/{id}', [SecretaryController::class, 'show'])->name('secretary.show');
    Route::get('/chairman/registrations/{id}', [ChairmanController::class, 'show'])->name('chairman.show');

    // Fee collection & receipt printing
    Route::post('/cashier/collect-fee/{id}', [CashierController::class, 'collectFee'])->name('cashier.collect-fee');
    Route::get('/cashier/print-receipt/{id}', [CashierController::class, 'printReceipt'])->name('cashier.print-receipt');

    // Document Viewing Route
Route::get('/documents/view/{filename}', [DocumentController::class, 'viewDocument'])->name('documents.view');
{
    $path = storage_path("app/documents/$filename");

    // ✅ Ensure file exists
    if (!file_exists($path)) {
        abort(404, 'File not found.');
    }

    // ✅ Check if user is authenticated (Optional)
    if (!Auth::check()) {
        abort(403, 'Unauthorized access.');
    }

    return response()->file($path);
})->name('documents.view');
Route::post('/registrations/{id}/forward-to-chairman', [RegistrationController::class, 'forwardToChairman'])
    ->name('registrations.forward_to_chairman');
});

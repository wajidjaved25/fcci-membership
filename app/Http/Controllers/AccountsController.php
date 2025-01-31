<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountsController extends Controller
{
    /**
     * Show registrations that require auditing.
     */
    public function index()
    {
        if (Auth::user()->role !== 'accounts_audit') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $registrations = Registration::where('status', 'fee_paid')->get();
        return view('accounts.dashboard', compact('registrations'));
    }

    /**
     * Approve audit and mark for committee review.
     */
    public function auditDocuments($id)
    {
        if (Auth::user()->role !== 'accounts_audit') {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $registration = Registration::findOrFail($id);
        $registration->update(['status' => 'audited']);

        return redirect()->route('accounts.dashboard')->with('success', 'Documents audited successfully.');
    }
public function show($id)
{
    if (Auth::user()->role !== 'accounts_audit') {
        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }

    // ✅ Ensure related data is loaded
    $registration = Registration::with(['directorsPartners', 'documents'])->findOrFail($id);
    return view('accounts.show', compact('registration'));
}


}

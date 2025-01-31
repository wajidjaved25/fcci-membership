<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;

class MembershipSupervisorController extends Controller
{
    /**
     * Show registrations that require document verification.
     */
    public function index()
    {
        if (Auth::user()->role !== 'membership_supervisor') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        // ✅ Optional: Use pagination if there are many registrations
        $registrations = Registration::where('status', 'pending')->paginate(10);
        return view('supervisor.dashboard', compact('registrations'));
    }

    /**
     * Verify documents and move the application to the fee collection step.
     */
    public function verifyDocuments($id)
    {
        if (Auth::user()->role !== 'membership_supervisor') {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        // ✅ Fetch the registration record
        $registration = Registration::findOrFail($id);

        // ✅ Update registration status
        $registration->update([
            'status' => 'fee_due',
            'payment_status' => 'pending' // Now it will show in cashier dashboard
        ]);

        return redirect()->route('supervisor.dashboard')->with('success', 'Documents verified. Fee payment required.');
    }
    public function rejectApplication(Request $request, $id)
{
    if (Auth::user()->role !== 'membership_supervisor') {
        return redirect()->route('home')->with('error', 'Unauthorized action.');
    }

    $request->validate([
        'rejection_reason' => 'required|string|max:1000', // ✅ Ensure reason is provided
    ]);

    $registration = Registration::findOrFail($id);

    $registration->update([
        'status' => 'rejected',
        'rejection_reason' => $request->input('rejection_reason'),
        'rejected_by' => Auth::id(),
    ]);

    return redirect()->route('supervisor.dashboard')->with('error', 'Application rejected.');
}

    /**
     * Show a specific registration with related details.
     */
    public function show($id)
    {
        if (Auth::user()->role !== 'membership_supervisor') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        // ✅ Ensure related data is loaded
        $registration = Registration::with(['directorsPartners', 'documents'])->findOrFail($id);
        return view('supervisor.show', compact('registration'));
    }
}

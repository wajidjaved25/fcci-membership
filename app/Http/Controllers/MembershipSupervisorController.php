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

        $registrations = Registration::where('status', 'pending')->get();
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

    return $this->updateRegistrationStatus($id, 'fee_due', 'Documents verified. Fee payment required.');
}

public function show($id)
{
    if (Auth::user()->role !== 'membership_supervisor') {
        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }

    $registration = Registration::with(['directorsPartners', 'documents'])->findOrFail($id);
    return view('supervisor.show', compact('registration'));
}

}

<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecretaryController extends Controller
{
    /**
     * Show registrations that need provisional approval.
     */
    public function index()
    {
        if (Auth::user()->role !== 'dg_secretary') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $registrations = Registration::where('status', 'provisionally_approved')->get();
        return view('secretary.dashboard', compact('registrations'));
    }

    /**
     * Approve provisional membership and send to committee review.
     */
    public function approveProvisionalMembership($id)
    {
        if (Auth::user()->role !== 'dg_secretary') {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $registration = Registration::findOrFail($id);
        $registration->update(['status' => 'committee_review']);

        return redirect()->route('secretary.dashboard')->with('success', 'Provisional membership approved.');
    }
public function show($id)
{
    $registration = Registration::with(['directorsPartners', 'documents'])->findOrFail($id);
    
    return view('accounts.show', compact('registration'));
}
}

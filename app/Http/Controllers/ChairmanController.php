<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChairmanController extends Controller
{
    /**
     * Show registrations awaiting final approval.
     */
    public function index()
{
    $registrations = Registration::where('status', 'provisionally_approved')->get();
    return view('chairman.dashboard', compact('registrations'));
}

    /**
     * Grant final membership approval.
     */
    public function grantFinalApproval($id)
    {
        if (Auth::user()->role !== 'chairman_president') {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $registration = Registration::findOrFail($id);
        $registration->update(['status' => 'final_approval']);

        return redirect()->route('chairman.dashboard')->with('success', 'Membership approved.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;

class MemberController extends Controller
{
    /**
     * Show member dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)->first();

        return view('member.dashboard', compact('registration'));
    }

    /**
     * Apply for membership renewal.
     */
    public function renewMembership()
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)->first();

        if (!$registration) {
            return redirect()->route('member.dashboard')->with('error', 'No membership record found.');
        }

        $registration->update(['status' => 'renewal_requested']);

        return redirect()->route('member.dashboard')->with('success', 'Membership renewal requested.');
    }

    /**
     * Apply for Visa Letter Facilitation.
     */
    public function requestVisaLetter()
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)->first();

        if (!$registration) {
            return redirect()->route('member.dashboard')->with('error', 'No membership record found.');
        }

        // Here you can add logic to store the visa request if needed.
        return redirect()->route('member.dashboard')->with('success', 'Visa letter facilitation requested.');
    }
}


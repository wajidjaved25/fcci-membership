<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use App\Services\SmsService;

class ChairmanController extends Controller
{
    /**
     * Show registrations that require final chairman approval.
     */
    public function index()
    {
        if (Auth::user()->role !== 'chairman_president') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $registrations = Registration::where('status', 'provisionally_approved')->get();
        return view('chairman.dashboard', compact('registrations'));
    }

    /**
     * Show detailed application for final review.
     */
    public function show($id)
    {
        if (Auth::user()->role !== 'chairman_president') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $registration = Registration::with(['directorsPartners', 'documents'])->findOrFail($id);
        return view('chairman.show', compact('registration'));
    }

    /**
     * Approve membership application.
     */
    public function approveMembership($id, SmsService $smsService)
    {
        if (Auth::user()->role !== 'chairman_president') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $registration = Registration::findOrFail($id);
        $registration->update(['status' => 'final_approval']);

        // ✅ Send SMS confirming final approval
    $message = "Dear {$registration->company_name}, congratulations! Your membership has been fully approved.";
    $smsService->sendSms($registration->mobile, $message);

        return redirect()->route('chairman.dashboard')->with('success', 'Membership application approved.');
    }

    /**
     * Reject membership application.
     */
    public function rejectMembership(Request $request, $id)
    {
        if (Auth::user()->role !== 'chairman_president') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $registration = Registration::findOrFail($id);
        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return redirect()->route('chairman.dashboard')->with('success', 'Membership application rejected.');
    }
}


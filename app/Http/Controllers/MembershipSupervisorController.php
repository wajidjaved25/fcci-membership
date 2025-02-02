<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use App\Services\SmsService;

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
    public function verifyDocuments($id, SmsService $smsService)
    {
        if (Auth::user()->role !== 'membership_supervisor') {
            \Log::error("❌ Unauthorized access attempt by User ID: " . Auth::id());
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }
        \Log::info("✅ Supervisor is verifying documents for Registration ID: $id");
        // ✅ Fetch the registration record
        $registration = Registration::findOrFail($id);

        \Log::info("✅ Found registration: " . json_encode($registration));

        // ✅ Update registration status
        $registration->update([
            'status' => 'fee_due',
            'payment_status' => 'pending' // Now it will show in cashier dashboard
        ]);

        \Log::info("✅ Registration updated successfully: " . json_encode($registration));

     // ✅ Manually resolve the SMS service
     try {
        $smsService = app(SmsService::class);
        if (!$smsService) {
            \Log::error("❌ SmsService could not be resolved.");
            return redirect()->route('supervisor.dashboard')->with('error', 'Failed to send SMS.');
        }

        $message = "Dear {$registration->company_name}, your documents have been verified. Please proceed with fee payment within next 7 days.";
        
        \Log::info("📨 Attempting to send SMS: {$message} to {$registration->mobile}");

        $smsSent = $smsService->sendSms($registration->mobile, $message);

        if ($smsSent) {
            \Log::info("✅ SMS sent successfully to {$registration->mobile}");
        } else {
            \Log::error("❌ SMS sending failed.");
        }

    } catch (\Exception $e) {
        \Log::error("❌ Exception while sending SMS: " . $e->getMessage());
    }

    return redirect()->route('supervisor.dashboard')->with('success', 'Documents verified. Fee payment required.');
}
    public function rejectApplication(Request $request, $id, SmsService $smsService)
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

    // ✅ Send SMS Notification
    $message = "Dear {$registration->company_name}, your application has been rejected. Please contact the chamber office.";
    $smsService->sendSms($registration->mobile, $message);

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

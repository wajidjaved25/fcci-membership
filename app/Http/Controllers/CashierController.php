<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MembershipFee;
use Barryvdh\DomPDF\Facade\Pdf;

class CashierController extends Controller
{
    public function index()
    {
        // Show only applications that require payment
        $registrations = Registration::where('payment_status', 'pending')->get();
        return view('cashier.dashboard', compact('registrations'));
    }

    public function collectFee(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);
        $membershipFee = MembershipFee::where('membership_class', $registration->membership_class)->first();

        if (!$membershipFee) {
            return redirect()->back()->with('error', 'Membership fee not set.');
        }

        // Update fee status
        $registration->update([
            'fee_paid' => $membershipFee->fee_amount,
            'fee_paid_at' => now(),
            'payment_status' => 'paid',
            'status' => 'fee_paid' // Update status after fee payment
        ]);

        return redirect()->route('cashier.dashboard')->with('success', 'Fee collected successfully.');
    }
}
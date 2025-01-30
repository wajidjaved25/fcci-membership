<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\MembershipFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CashierController extends Controller
{
    public function index()
    {
        // Show only applications that require payment (payment_status = 'pending')
        $registrations = Registration::where('payment_status', 'pending')->get();

        // Fetch membership fee details
        foreach ($registrations as $registration) {
            $membershipFee = MembershipFee::where('membership_class', $registration->membership_class)->first();
            $registration->fee_amount = $membershipFee ? $membershipFee->fee_amount : 'N/A';
        }

        return view('cashier.dashboard', compact('registrations'));
    }

    public function collectFee(Request $request, $id)
    {
        try {
            $registration = Registration::findOrFail($id);
            $membershipFee = MembershipFee::where('membership_class', $registration->membership_class)->first();

            if (!$membershipFee) {
                return redirect()->back()->with('error', 'Membership fee not set.');
            }

            // Update registration with fee details
            $registration->update([
                'fee_paid' => $membershipFee->fee_amount,
                'fee_paid_at' => now(),
                'payment_status' => 'paid',
                'status' => 'fee_paid' // Update status
            ]);

            Log::info("Fee Collected: Rs. {$membershipFee->fee_amount} for Registration ID: {$registration->id}");

            return redirect()->route('cashier.dashboard')->with('success', 'Fee collected successfully.');
        } catch (\Exception $e) {
            Log::error("Fee Collection Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while collecting the fee.');
        }
    }

    public function printReceipt($id)
    {
        $registration = Registration::findOrFail($id);

        $pdf = Pdf::loadView('pdf.receipt', compact('registration'));
        return $pdf->download('receipt_' . $registration->id . '.pdf');
    }
}
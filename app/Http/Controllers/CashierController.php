<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\MembershipFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CashierController extends Controller
{
    /**
     * Display registrations that need fee collection.
     */
    public function index()
    {
        $registrations = Registration::where('status', 'fee_due')
            ->where('payment_status', 'pending') // Only show applications needing fee collection
            ->get();

        // Attach membership fee details dynamically
        foreach ($registrations as $registration) {
            $membershipFee = MembershipFee::where('membership_class', $registration->membership_class)->first();
            $registration->fee_amount = $membershipFee ? $membershipFee->fee_amount : 0;
        }

        return view('cashier.dashboard', compact('registrations'));
    }

    /**
     * Collect Fee & Update Registration Status
     */
    public function collectFee(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);

        if (Auth::user()->role !== 'cashier') {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        Log::info("Collecting Fee for Registration ID: {$id}");
        Log::info("Expected Fee Amount from DB: " . $registration->fee_amount);
        Log::info("Received Fee Amount: " . $request->fee_amount);

        $enteredFee = floatval($request->fee_amount);
        $expectedFee = floatval($registration->fee_amount);

        if ($enteredFee != $expectedFee) {
            return response()->json([
                'success' => false,
                'message' => "Incorrect fee amount. Expected: Rs. {$expectedFee}, but received: Rs. {$enteredFee}",
            ], 422);
        }

        // Update the registration with fee details
        $registration->update([
            'status' => 'fee_paid',
            'payment_status' => 'paid',
            'fee_paid_at' => now(),
            'collected_fee_amount' => $enteredFee,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fee collected successfully.',
            'redirect_url' => route('cashier.print-receipt', ['id' => $registration->id]),
        ]);
    }

    /**
     * Generate & Print Receipt
     */
    public function printReceipt($id)
    {
        $registration = Registration::findOrFail($id);

        // Generate PDF
        $pdf = Pdf::loadView('pdf.receipt', compact('registration'));

        // Store PDF in storage
        $pdfPath = 'receipts/receipt_' . $registration->id . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Provide a direct download link
        return response()->json([
            'success' => true,
            'message' => 'Receipt generated successfully.',
            'download_url' => Storage::disk('public')->url($pdfPath),
        ]);
    }
}

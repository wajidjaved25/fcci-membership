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
    public function index()
    {
        $registrations = Registration::where('status', 'fee_due')
                                ->where('payment_status', 'pending') // Only show applications needing fee collection
                                ->get();

        // Fetch membership fee details
        foreach ($registrations as $registration) {
            $membershipFee = MembershipFee::where('membership_class', $registration->membership_class)->first();
            $registration->fee_amount = $membershipFee ? $membershipFee->fee_amount : 'N/A';
        }

        return view('cashier.dashboard', compact('registrations'));
    }

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

    $registration->update([
        'status' => 'fee_paid',
        'payment_status' => 'paid',
        'fee_paid_at' => now(),
	'fee_paid' => '$membershipFee'
        
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Fee collected successfully.',
        'redirect_url' => route('cashier.print-receipt', ['id' => $registration->id]),
    ]);
}


// Generate and Print Receipt
public function printReceipt($id)
{
    $registration = Registration::findOrFail($id);

    // Generate PDF
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', compact('registration'));

    // Store PDF in storage
    $pdfPath = 'receipts/receipt_' . $registration->id . '.pdf';
    \Illuminate\Support\Facades\Storage::disk('public')->put($pdfPath, $pdf->output());

    // Redirect to auto-download the receipt
    return response()->file(storage_path('app/public/' . $pdfPath), [
        'Content-Disposition' => 'inline; filename="receipt.pdf"'
    ]);
}

}
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
    $request->validate([
        'fee_amount' => 'required|numeric|min:1',
    ]);

    $registration = Registration::findOrFail($id);

    if ($registration->fee_amount != $request->fee_amount) {
        return response()->json(['success' => false, 'message' => 'Fee amount does not match!']);
    }

    // ✅ Update fee status
    $registration->update([
        'status' => 'fee_paid',
        'payment_status' => 'Paid',
        'fee_paid_at' => now(),
    ]);

    // ✅ Generate Receipt URL
    $receiptUrl = route('cashier.receipt', ['id' => $registration->id]);

    return response()->json([
        'success' => true,
        'redirect_url' => $receiptUrl, // ✅ Redirect for auto-print
    ]);
}
public function showReceipt($id)
{
    $registration = Registration::findOrFail($id);

    if ($registration->payment_status !== 'Paid') {
        return redirect()->route('cashier.dashboard')->with('error', 'Receipt not available.');
    }

    return view('cashier.receipt', compact('registration'));
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
